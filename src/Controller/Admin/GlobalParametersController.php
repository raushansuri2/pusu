<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\Filesystem\File;

class GlobalParametersController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $layoutTitle = 'Admin::GlobalParameters';
        $this->set(compact('layoutTitle'));
        $this->viewBuilder()->setLayout('Admin/admin');

        $id = 1;
        $globalparameters = $this->fetchTable('GlobalParameters')->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $globalparameters = $this->fetchTable('GlobalParameters')->patchEntity($globalparameters, $this->request->getData());

            if ($this->fetchTable('GlobalParameters')->save($globalparameters)) {
                $this->Flash->success(__('Global parameter has been updated successfully.'));
                return $this->redirect(['controller' => 'GlobalParameters', 'action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update global parameters. Please try again.'));
            }
        }

        $this->set(compact('globalparameters'));
    }
    
    public function usps()
    {
        $layoutTitle = 'Admin::USPS';
        $this->viewBuilder()->setLayout('Admin/admin');

        $uspsData = $this->fetchTable('Usps')->find('all')->toArray();

        $this->set(compact('layoutTitle', 'uspsData'));
    }
    
    public function uspsAdd()
    {
        $layoutTitle = 'Admin::AddUSPS';
        $this->viewBuilder()->setLayout('Admin/admin');
    
        $usps = null;
        if ($this->request->is('post')) {
            // Validate required input data
            $environment = $this->request->getData('environment');
            $clientId = $this->request->getData('client_id');
    
            if (empty($environment) || empty($clientId)) {
                $this->Flash->error(__('Environment and Client ID are required.'));
                $usps = $this->fetchTable('Usps')->newEmptyEntity();
            } else {
                // Check if the USPS entry already exists based on client_id and environment
                $existingUsps = $this->fetchTable('Usps')->find()
                    ->where(['client_id' => $clientId, 'environment' => $environment])
                    ->first();
    
                $usps = $existingUsps ?: $this->fetchTable('Usps')->newEmptyEntity();
                $usps = $this->fetchTable('Usps')->patchEntity($usps, $this->request->getData());
    
                $apiUrl = ($environment === 'live') 
                    ? 'https://apis.usps.com/oauth2/v3/token' 
                    : 'https://apis-tem.usps.com/oauth2/v3/token';
    
                // Prepare the API request using cURL
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);
    
                // Prepare the data as a JSON string
                $data = json_encode([
                    'grant_type' => $usps->grant_type,
                    'client_id' => $usps->client_id,
                    'client_secret' => $usps->client_secret,
                ]);
    
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
                // Execute the cURL request
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
                // Check for cURL errors
                if ($response === false) {
                    $this->Flash->error(__('cURL request failed: ') . curl_error($ch));
                    curl_close($ch);
                } else {
                    curl_close($ch);
    
                    if ($httpCode === 200) {
                        $apiResponse = json_decode($response, true);
    
                        // Validate API response
                        if (!is_array($apiResponse)) {
                            $this->Flash->error(__('Invalid API response from USPS.'));
                        } else {
                            // Save the API response to the entity
                            $usps->access_token = $apiResponse['access_token'] ?? null;
                            $usps->token_type = $apiResponse['token_type'] ?? null;
                            $usps->status = $apiResponse['status'] ?? null;
                            $usps->scope = $apiResponse['scope'] ?? null;
                            $usps->issuer = $apiResponse['issuer'] ?? null;
                            $usps->application_name = $apiResponse['application_name'] ?? null;
                            $usps->api_products = $apiResponse['api_products'] ?? null;
                            $usps->public_key = $apiResponse['public_key'] ?? null;
    
                            // Validate and convert issued_at from milliseconds to seconds
                            if (isset($apiResponse['issued_at']) && is_numeric($apiResponse['issued_at'])) {
                                $issuedAtInSeconds = floor($apiResponse['issued_at'] / 1000);
    
                                $issuedDateTime = new \DateTime();
                                $issuedDateTime->setTimestamp($issuedAtInSeconds);
                                $usps->issued_at = $issuedDateTime->format('Y-m-d H:i:s');
    
                                // Validate and calculate the expiration time
                                $expiresIn = isset($apiResponse['expires_in']) && is_numeric($apiResponse['expires_in']) 
                                    ? (int)$apiResponse['expires_in'] 
                                    : 0;
                                if ($expiresIn > 0) {
                                    $expirationDateTime = clone $issuedDateTime;
                                    $expirationDateTime->modify("+{$expiresIn} seconds");
                                    $usps->expires_in = $expirationDateTime->format('Y-m-d H:i:s');
                                } else {
                                    $usps->expires_in = null;
                                    $this->Flash->warning(__('Expires_in value is invalid or missing.'));
                                }
    
                                // Save the entity to the database
                                if ($this->fetchTable('Usps')->save($usps)) {
                                    $this->Flash->success(__('The USPS data has been saved.'));
                                    return $this->redirect(['action' => 'usps']);
                                } else {
                                    $this->Flash->error(__('The USPS data could not be saved. Please, try again.'));
                                }
                            } else {
                                $this->Flash->error(__('Invalid issued_at value from USPS API.'));
                            }
                        }
                    } else {
                        $this->Flash->error(__('Failed to retrieve access token from USPS API. Please check your credentials and try again.'));
                    }
                }
            }
        }
    
        $this->set(compact('layoutTitle', 'usps'));
    }
    
    public function renew($uspsId)
    {
        $usps = $this->fetchTable('Usps')->get($uspsId); // Use fetchTable
    
        $environment = $usps->environment;
        $apiUrl = ($environment === 'live') 
            ? 'https://apis.usps.com/oauth2/v3/token' 
            : 'https://apis-tem.usps.com/oauth2/v3/token';
    
        // Prepare the API request using cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
    
        // Prepare the data as a JSON string
        $data = json_encode([
            'grant_type' => $usps->grant_type,
            'client_id' => $usps->client_id,
            'client_secret' => $usps->client_secret,
        ]);
    
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
        // Execute the cURL request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        // Check if the API call was successful
        if ($httpCode === 200) {
            $apiResponse = json_decode($response, true);
    
            // Save the API response to the entity
            $usps->access_token = $apiResponse['access_token'] ?? null;
            $usps->token_type = $apiResponse['token_type'] ?? null;
            $usps->status = $apiResponse['status'] ?? null;
            $usps->scope = $apiResponse['scope'] ?? null;
            $usps->issuer = $apiResponse['issuer'] ?? null;
            $usps->application_name = $apiResponse['application_name'] ?? null;
            $usps->api_products = $apiResponse['api_products'] ?? null;
            $usps->public_key = $apiResponse['public_key'] ?? null;
    
            // Validate and convert issued_at from milliseconds to seconds
            if (isset($apiResponse['issued_at']) && is_numeric($apiResponse['issued_at'])) {
                $issuedAtInSeconds = floor($apiResponse['issued_at'] / 1000);
    
                // Create a DateTime object from the issued_at timestamp
                $issuedDateTime = new \DateTime();
                $issuedDateTime->setTimestamp($issuedAtInSeconds);
    
                // Format the issued_at datetime
                $usps->issued_at = $issuedDateTime->format('Y-m-d H:i:s');
    
                // Calculate the expiration time
                $expiresIn = $apiResponse['expires_in'] ?? 0;
                $expirationDateTime = clone $issuedDateTime;
                $expirationDateTime->modify("+{$expiresIn} seconds");
    
                // Format the expiration datetime
                $usps->expires_in = $expirationDateTime->format('Y-m-d H:i:s');
            } else {
                $this->Flash->error(__('Invalid issued_at value from USPS API.'));
                return $this->redirect(['action' => 'usps']);
            }
    
            // Save the entity to the database
            if ($this->fetchTable('Usps')->save($usps)) { // Use fetchTable
                $this->Flash->success(__('The USPS data has been renewed successfully.'));
            } else {
                $this->Flash->error(__('The USPS data could not be renewed. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Failed to retrieve access token from USPS API. Please check your credentials and try again.'));
        }
    
        return $this->redirect(['action' => 'usps']);
    }
    
}