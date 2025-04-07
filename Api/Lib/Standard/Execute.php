<?php
namespace Api\Lib\Standard;

use Api\Business\FactoryBusiness;
use Api\Controller\Standard\Controller;
use Api\Exceptions\FactoryException;
use Api\Exceptions\Standard\PDODb\CanNotConnectDbException;
use Api\Lib\Current\Routes;
use Api\Lib\Log\Log;
use Api\Lib\Validator\ErrorDetails;
use Api\Repository\Mapper\Standard\ResponseMap;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;
use Throwable;

class Execute
{

    private static $instance = null;
    private Log $logger;
    private $response;
    private $isToSaveLog = true;

    public static function getInstance(): Execute
    {
        if (self::$instance == null) {
            self::$instance = new Execute();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->logger = new Log();
    }

    private function init(): void
    {
        Routes::getInstance()->init();
        $this->initialVerifications();
    }

    private function initialVerifications(): void
    {
        FactoryBusiness::create('Queues\InitialVerifications\Check\Check')->init();
    }

    public function run(): void
    {
        try {
            $this->init();
            $this->runEndpoint();
        } catch (CanNotConnectDbException $exc) {
            $this->isToSaveLog = false;
            $this->appendResponseMapException($exc);
        } catch (PDOException $exc) {
            // $this->logger->error($exc);
            $this->response->addField('error', ['message' => 'Falha Interna. Por favor, tente novamente.']);
        } catch (\Exception $exc) {
            // $this->logger->error( $exc);
            $this->appendResponseMapException($exc);
        } catch (Throwable $th) {
            // $this->logger->error($th);
            http_response_code(500);
            $this->appendResponseMapThrowable($th);
        } finally {
            $json = $this->getJsonOrFail();
            $this->saveExecutionLog($json); 
            die($json);
        }
    }

    private function runEndpoint(): void
    {
        $router = Routes::getInstance()->router();
        $router->dispatch();
        if ($router->error()) {
            throw FactoryException::create('Standard\EndpointKey\InvalidEndpointException');
        }
        $this->response = Controller::$responseObject;
    }

    private function appendResponseMapException(Exception $ex): void
    {
        $this->response = new ResponseMap();
        $error = array('message' => $ex->getMessage());
        $errorDetails = ErrorDetails::getInstance();
        if ($errorDetails->hasDetails() || $errorDetails->hasDetailsWarning()) {
            $error['details'] = $errorDetails->getDetails();
        }
        $exceptionName = get_class($ex);
        if (IS_OFFLINE) {
            $error['exceptionClass'] = $exceptionName;
        }

        $this->response->addField('error', $error);
    }

    private function appendResponseMapThrowable(Throwable $ex): void
    {
        $this->response = new ResponseMap();
        $error = array('message' => 'Falha interna. Por favor, tente novamente.');

        $exceptionName = get_class($ex);

        if (IS_OFFLINE) {
            $error['exceptionClass'] = $exceptionName;
            $error['details'] = $ex->getMessage();
            $error['trace'] = $ex->getTrace();
        }

        $this->response->addField('error', $error);
    }

    private function getResponse(): string
    {
        $response = $this->response->renderObject();
        $detailsObj = ErrorDetails::getInstance();
        if ($detailsObj->hasDetailsWarning()) {
            $warning = array(
                'warning' => array(
                    'details' => $detailsObj->getDetails()
                )
            );
            $response = array_merge($warning, $response);
        }

        $customResponse = CustomResponse::getInstance();
        if ($customResponse->hasInfo()) {
            $response['AutomatedTests'] = $customResponse->getInfo();
        }

        return \json_encode($response, JSON_PRETTY_PRINT);
    }

    private function getJsonOrFail(): string 
    {
        try {
            return $this->getResponse();
        } catch (Exception $ex) {
            // $this->logger->error($ex);
            return json_encode(['error' => ['message' => 'Falha interna. Por favor, tente novamente']]);
        } catch (Throwable $th) {
            http_response_code(500);
            // $this->logger->error($th);
            return json_encode(['error' => ['message' => 'Falha interna. Por favor, tente novamente']]);
        }
    }

    private function saveExecutionLog(string $response): void
    {
        if (!$this->isToSaveLog) return;

        // LogBuilder::getInstance()
        //     ->appendContextResponse($response);

        // $this->logger->saveLog();
    }
}