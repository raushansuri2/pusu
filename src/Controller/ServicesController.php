<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
use App\Controller\AppController;
use App\Controller\Admin\Component\ImgComponent;
use Cake\Core\Configure;
use Cake\View\Helper\HtmlHelper;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Routing\Router;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Psr\Http\Message\UploadedFileInterface;
use Cake\I18n\FrozenTime;



use Cake\View\Helper\PaginatorHelper;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

$req_dump = '--------------------'.date('Y-m-d H:i:s').'-----------------------------';
$inputJSON = file_get_contents('php://input');
if($inputJSON){
	$input = json_decode($inputJSON, TRUE);
$req_dump .= print_r($input, TRUE);
}
$req_dump .= print_r($_POST, TRUE);
$req_dump .= print_r($_FILES, TRUE);
$fp = fopen('data/data.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);

class ServicesController extends AppController
{
    var $profilePath 	= 'https://demo5.evirtualservices.net/datingapp/img/uploads/users/';
	var $postPath 		= 'https://demo5.evirtualservices.net/datingapp/img/uploads/post/';
	var $multiImagePath = 'https://demo5.evirtualservices.net/datingapp/img/uploads/multiimage/';
	function index(){
		Configure::write('Session.checkAgent', false);
		$response=array();
		//pr($this->request->getData()); die;
		//$this->layout='';
		if($this->request->getData('action') !=''){
			//$this->request->getData()=$this->request->getData();
		}else{
			$inputJSON = file_get_contents('php://input');
			if($inputJSON){
				$this->request = $this->request->withParsedBody([json_decode($inputJSON, TRUE)]);
			}
		}
		$field = $this->request->getData('action');
		$action = is_string($field) ? trim($field) : '';
		//$action = trim($this->request->getData('action'));
		//echo $action;
		//pr($this->request->getData()); die;
		if(!empty($action)){
			$response = $this->$action();
			//$response = $this->$this->request->getData('action')();
			//unset($this->request->getData['action']);
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'Invalid action key';
		}
		echo json_encode($response); die;
	}
	
	function welcome(){
		$keyFeatures = Configure::read('keyFeatures');
		ksort($keyFeatures['WELCOME']);
		$data = array();
		foreach($keyFeatures['WELCOME'] as $key=>$VV){
			$data[$key]['id'] = $key;
			$data[$key]['name'] = $VV;
		}
		$response['status'] = 'success';
		$response['data'] = array_values($data);
		return  $response;
	}
	
	private function registration(){
		$usersTable = $this->fetchTable('Users');
		$request = $this->getRequest();
		$data = $request->getData();

		$response = [];

		if (empty($data['email']) || empty($data['firstName']) || empty($data['password'])) {
			return [
				'status' => 'Fails',
				'msg' => 'Email, contactNumber, fullName and password required'
			];
		}

		$data['email'] = trim($data['email']);
		$data['password'] = str_replace(' ', '', $data['password']);
		$data['Pcode'] = base64_encode($data['password']);

		$existingUser = $usersTable->find()
			->where(['OR'=>[
							['Users.contactNumber' => $data['contactNumber']],['Users.email' => $data['email']]
						]
					])
			->first();

		if ($existingUser) {
			return [
				'status' => 'Fails',
				'msg' => 'Email or contactNumber already registered with LGBT-TOGO App',
			];
		}

		$user = $usersTable->newEmptyEntity();
		$user = $usersTable->patchEntity($user, $data, ['validate' => false]);

		$verificationToken = $this->random_password(50);
		$sessionToken = $this->random_password(50);

		$user->verification_token = $verificationToken;
		$user->created = FrozenTime::now();
		$user->username = $data['email'];
		$user->status = '1';
		$user->role = 'Member';

		// Handle image upload
		$image = $request->getData('image');
		if ($image instanceof UploadedFileInterface && $image->getError() === UPLOAD_ERR_OK) {
			$fileName = time() . preg_replace('/[^a-zA-Z0-9.]/', '', $image->getClientFilename());
			$image->moveTo(WWW_ROOT . 'img/uploads/users/' . $fileName);
			$user->profile_picture = $fileName;
		}

		// Handle BImage upload
		$bImage = $request->getData('BImage');
		if ($bImage instanceof UploadedFileInterface && $bImage->getError() === UPLOAD_ERR_OK) {
			$fileName2 = time() . preg_replace('/[^a-zA-Z0-9.]/', '', $bImage->getClientFilename());
			$bImage->moveTo(WWW_ROOT . 'img/uploads/users/' . $fileName2);
			$user->BImage = $fileName2;
		}

		if ($usersTable->save($user)) {
			//$request->getSession()->write('session_token', $sessionToken);

			//$verificationLink = Configure::read('App.siteurl') . 'users/verifyEmail/' . $verificationToken ;
			$subject = "Welcome to LGBT-TOGO App! Please verify your email.";
			$message = "Dear " . ucfirst($data['firstName']);
			$message .= "<br>Welcome to LGBT-TOGO App! We're delighted to have you as a new member of our platform.";
			// $message .= "<br>Please verify your email by clicking the link below:";
			// $message .= "<br><br><a href='" . $verificationLink . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email</a>";
			// $message .= "<br><br><strong>Note:</strong> Please open this link in the same browser to ensure proper verification.";

			$this->phpemail($data['email'], $subject, $message);

			$profile = $this->profile($user->id);

			return [
				'status' => 'success',
				'msg' => 'Data saved successfully.',
				'data' => $profile['data'] ?? [],
			];
		}

		return [
			'status' => 'Fails',
			'msg' => 'Something went wrong. Please try again.',
		];
	}
	
	
	private function socialLoginAction(){
		$UsersTable = $this->fetchTable('Users');
		if($this->request->getData('socialId') !='' && $this->request->getData('socialType') !=''){
			
			// if($this->request->getData('image')){
			// 	$this->request->getData('profile_picture')  = $this->request->getData('image');
			// }
			$usr = $UsersTable->find()->where(['Users.socialId' => $this->request->getData('socialId'),'Users.socialType' => $this->request->getData('socialType')])->first();
			$users = $UsersTable->find()->where(['Users.email' => $this->request->getData('email')])->first();
			if(empty($usr)){
				if(empty($users)){
					$users = $UsersTable->newEmptyEntity();
				}
				$users = $UsersTable->patchEntity($users, $this->request->getData(), ['validate' => false]);
				$users['created'] 	= date('Y-m-d H:i:s');
				$users['username'] 	= $this->request->getData('email');
				$users['password'] 	= 'AB_'.rand(8,11);
				if($this->request->getData('image')){
					$users['profile_picture']  = $this->request->getData('image');
				}
				$users['status'] 	= '1';
				$users['role'] 		= 'Member';
				//$users['profile_picture'] 		= $this->request->getData('image');
				
				//$path = WWW_ROOT . 'img/uploads/feature/original/';
				if ($UsersTable->save($users)) {
					$response['status'] = 'success';
					$response['msg'] = 'Data save successfully.';
					$DD = $this->profile($users->id);
					$response['data'] = $DD['data'];
				}
			}else{
				$response['status'] = 'success';
				$response['msg'] = 'Data save successfully.';
				$res = $this->profile($usr->id);
				$response['data'] = $res['data'];
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'Email, fullName, socialId and socialType required';
		}
		return  $response;
	}
	
	private function login($slug=NULL){
		if($this->request->getData()){
			//pr($this->request->getData); die;
			$userTable = $this->fetchTable('Users');
			//$usr = $this->Users->find()->where(['Users.email' => $this->request->getData('email')])->first();
			$usr = $userTable->find()->where(['OR'=>[['Users.email' => $this->request->getData('email')],['Users.contactNumber' => $this->request->getData('email')]]])->first();
				if(!empty($usr)){
					$passwordHasher = new DefaultPasswordHasher();
						$check = $passwordHasher->check(trim($this->request->getData('password')),$usr->password);
						if($check && $usr->status == 1){
								//$userdetail['FreedomUsers'] = $usr;
								//$this->request->session()->write($userdetail);
								$usr = $userTable->patchEntity($usr, $this->request->getData(), ['validate' => false]);
								$userTable->save($usr);
								$response['status'] = 'success';
								$response['msg'] = 'Data save successfully.';
								$RES = $this->profile($usr->id);
								$response['data'] = $RES['data'];
								//$response['data'] = $this->profile($users->id);
						}else{
								$response['status'] = 'Fails';
								$response['msg'] = 'Email or password is wrong.';
						}
				}else{
						$response['status'] = 'Fails';
						$response['msg'] = 'Email or password is wrong.';
				}
		}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'Email or password is required.';
		}
			return  $response;
	}
	
	private function editprofile(){
		if($this->request->getData('userId') !=''){
			$userTable = $this->fetchTable('Users');
			$request = $this->getRequest();
			$data = $this->request->getData();
			$users = $userTable->find()->where(['Users.id' => $data['userId']])->first();
			if(!empty($users)){
				//@unset($this->request->getData('email'));
				//echo "<pre>";print_r($this->request->getData);print_r($users); die;
				$old_1 = $users->profile_picture; 
				$old_2 = $users->BImage;
				unset($data['image'], $data['BImage'], $data['profile_ID_image']);
				$users = $userTable->patchEntity($users, $data, ['validate' => false]);
				$fileName = '';
				$fileName2 = '';
				

				$image = $request->getData('image');
				if ($image instanceof UploadedFileInterface && $image->getError() === UPLOAD_ERR_OK) {
					$fileName = time() . preg_replace('/[^a-zA-Z0-9.]/', '', $image->getClientFilename());
					$image->moveTo(WWW_ROOT . 'img/uploads/users/' . $fileName);
					$users->profile_picture = $fileName;
					//@unlink(WWW_ROOT . 'img/uploads/users/'.$old_1);
				}

				// Handle BImage upload
				$bImage = $request->getData('BImage');
				if ($bImage instanceof UploadedFileInterface && $bImage->getError() === UPLOAD_ERR_OK) {
					$fileName2 = time() . preg_replace('/[^a-zA-Z0-9.]/', '', $bImage->getClientFilename());
					$bImage->moveTo(WWW_ROOT . 'img/uploads/users/' . $fileName2);
					$users->BImage = $fileName2;
					//@unlink(WWW_ROOT . 'img/uploads/users/'.$old_2);
				}

				$profileIDImage = $request->getData('profile_ID_image');
				if ($profileIDImage instanceof UploadedFileInterface && $profileIDImage->getError() === UPLOAD_ERR_OK) {
					$fileName3 = time() . preg_replace('/[^a-zA-Z0-9.]/', '', $profileIDImage->getClientFilename());
					$profileIDImage->moveTo(WWW_ROOT . 'img/uploads/users/' . $fileName3);
					
					// Delete old profile ID image if it exists
					if (!empty($users->profile_ID_image)) {
						@unlink(WWW_ROOT . 'img/uploads/users/' . $users->profile_ID_image);
					}

					$users->profile_ID_image = $fileName3;
				}

				

				//$path = WWW_ROOT . 'img/uploads/feature/original/';
				if ($userTable->save($users)) {
					// if(@$_FILES['profile_ID_image']['name'] !='' && $fileName3 !=''){
					// 	$target = WWW_ROOT.'/img/uploads/users/'.$fileName3;
					// 	$source=$_FILES['profile_ID_image']['tmp_name'];
					// 	@unlink(WWW_ROOT . 'img/uploads/users/'.$OLDImage);
					// 	move_uploaded_file($source,$target);
					// }

					$response['status'] = 'success';
					$response['msg'] = 'Data save successfully.';
					
					$res = $this->profile($users->id);
					$response['data'] = $res['data'];
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'UserId does not exist';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'UserID is required';
		}
		return  $response;
	}

	private function stripepaymentupdate(){
		$this->loadModel('Bookings');
		$this->loadModel('Requests'); $this->loadModel('Users');
		if($this->request->getData['requestId'] !='' && $this->request->getData['transactionId'] !='' && $this->request->getData['userId'] !='' && $this->request->getData['total_amount_with_admin_fee'] !='' && $this->request->getData['providerId'] !=''){
			$articles = TableRegistry::get('Requests');
			$query = $articles->query();
			$query->update()
				->set(['status'=>4])
				->where(['id' => $this->request->getData['requestId']])
				->execute();
			$request_detail = $this->Requests->find()->where(['Requests.id'=>$this->request->getData['requestId']])->first();
			$BookingData['request_id'] 		= $this->request->getData['requestId'];
			$BookingData['vendorId'] 		= $this->request->getData['providerId'];
			$BookingData['userId'] 			= $this->request->getData['userId'];
			$BookingData['UTYPE'] 			= (@$request_detail->UTYPE) ?  @$request_detail->UTYPE : '';
			if($BookingData['UTYPE'] == 2){
				$BookingData['typeofbusinessId'] 	= (@$request_detail->service_id) ?  @$request_detail->service_id : '';
			}else{
				$BookingData['typeOfServices'] 	= (@$request_detail->service_id) ?  @$request_detail->service_id : '';
			}
			
			$BookingData['adminFee'] 		= 0.16;
			$BookingData['serviceFee'] 		= $this->request->getData['service_fee_without_admin_fee'];
			$BookingData['totalAmount'] 	= $this->request->getData['total_amount_with_admin_fee'];
			$BookingData['currency'] 		= 'usd';
			$BookingData['transactionId'] 	= $this->request->getData['transactionId'];
			$BookingData['tokenId'] 		= $this->request->getData['tokenId'];
			$BookingData['payment_mode'] 	= $this->request->getData['payment_mode'];
			$BookingData['card_holder_name']= $this->request->getData['card_holder_name'];
			$BookingData['status'] 			= 2;
			
			$Booking = $this->Bookings->newEmptyEntity();
			$Booking = $this->Bookings->patchEntity($Booking, $BookingData, ['validate' => false]);
			$Booking['bookingDate'] = date('Y-m-d',strtotime(@$request_detail->requested_service_date));
			$Booking['created'] 	= date('Y-m-d H:i:s');
			$this->Bookings->save($Booking);


			$bookings = $this->Requests->find()->where(['Requests.id'=>$this->request->getData['requestId']])->contain(['Bookingservices'])->first();
			$vendor = $this->Users->find()->where(['Users.id'=>$bookings->serviceProvider])->first();
			$userS = $this->Users->find()->where(['Users.id'=>$bookings->sender])->first();
			$to = (@$vendor->email) ? @$vendor->email : 'raushan@mailinator.com'; 
			$subject="LGBT-TOGO App Request Paid";
			$message = "Dear " . ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).",<br>";
			$message .= '<br>Payment has been successfully received from the client for the booking.';	
			$message .= '<br><strong>Service Name</strong>: '.@$bookings->bookingservice->name;
			$message .= '<br><strong>Payment Amount</strong>: '.@$this->request->getData['service_fee_without_admin_fee'].' <strong>UDS</strong>';
			$message .= '<br><strong>Payment Date</strong>: '.date("F jS, Y, g:i a");
			$message .= '<br>Thank you for providing your services. Please ensure to deliver the services as scheduled.';
			
			$this->phpemail($to, $subject, $message);

			$response['status'] = "success";
			$response['msg'] = 'Payment updated successfull.';	
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'requestId, userId, providerId, transactionId, amount and status are required';	
		}
		return 	$response;
	}
	
	private function termAndConditions(){
		$pageTable = $this->fetchTable('Pages');
		
		$pages = $pageTable->find()->Where(['Pages.id' => 5])->first();
		$response['status'] = 'success';
		$response['msg'] = $pages->content;
		return  $response;
	}
	
	private function privacypolicy(){
		$pageTable = $this->fetchTable('Pages');
		
		$pages = $pageTable->find()->Where(['Pages.id' => 4])->first();
		$response['status'] = 'success';
		$response['msg'] = $pages->content;
		return  $response;
	}
	
	private function aboutus(){
		$pageTable = $this->fetchTable('Pages');
		
		$pages = $pageTable->find()->Where(['Pages.id' => 2])->first();
		$response['status'] = 'success';
		$response['msg'] = $pages->content;
		return  $response;
	}

	private function ourmission(){
		$pageTable = $this->fetchTable('Pages');
		
		$pages = $pageTable->find()->Where(['Pages.id' => 3])->first();
		$response['status'] = 'success';
		$response['msg'] = $pages->content;
		return  $response;
	}

	function profile($userId = NULL){
		$userTable = $this->fetchTable('Users');
		$postTable = $this->fetchTable('Posts');
		$friendTable = $this->fetchTable('Friends');
		$profilelikesTable = $this->fetchTable('Profilelikes');
		//$NotificationsTable = $this->fetchTable('Notifications');

			$userID = ($userId) ? $userId : $this->request->getData('userId');

			$OPID = $this->request->getData('other_profile_Id') ?? "";
			if($OPID !=""){
				$userD = $userTable->find()->where(['Users.id' => $OPID])->first();
			}else{
				$userD = $userTable->find()->where(['Users.id' => $userID])->first();
			}
			
			/*$userD = $this->Users->find()->where(['Users.id' => $userID])->contain(['Countries', 'States'])->first(); */
			//pr($userD); die;
			if($userD){
				$UserDetail['userId'] 				= ($userD->id) ? $userD->id : '';
				$UserDetail['firstName'] 			= ($userD->firstName) ? $userD->firstName : '';
				$UserDetail['email'] 				= ($userD->email) ? $userD->email : '';
				$UserDetail['role'] 				= ($userD->role) ? $userD->role : '';
				$UserDetail['address'] 				= ($userD->address) ? $userD->address : '';
				$UserDetail['gender'] 				= ($userD->gender) ? $userD->gender : '';
				$UserDetail['dob'] 					= ($userD->dob) ? $userD->dob : '';
				$UserDetail['cityname'] 			= (@$userD->cityname) ? @$userD->cityname : '';
				$UserDetail['interests'] 			= (@$userD->interests) ? @$userD->interests : '';
				$UserDetail['bio'] 					= (@$userD->bio) ? @$userD->bio : '';
				$UserDetail['story'] 				= ($userD->story) ? $userD->story : '';
				$UserDetail['your_belife'] 			= ($userD->your_belife) ? $userD->your_belife : '';
				$UserDetail['contactNumber'] 		= ($userD->contactNumber) ? $userD->contactNumber :'';
				$UserDetail['image'] 				= ($userD->profile_picture) ? $this->profilePath.$userD->profile_picture : '';
				if (!empty($userD->profile_picture) && strpos((string)$userD->profile_picture, "http") !== false) {
					$UserDetail['image'] = $userD->profile_picture;
				}
				$UserDetail['BImage'] 				= ($userD->BImage) ? $this->profilePath.$userD->BImage : '';
				$UserDetail['profile_ID_image'] 	= ($userD->profile_ID_image) ? $this->profilePath.$userD->profile_ID_image : '';
				$UserDetail['why_are_u_here'] 		= ($userD->why_are_u_here) ? $userD->why_are_u_here : "";
				$UserDetail['thought_of_day'] 		= ($userD->thought_of_day) ? $userD->thought_of_day : '';
				
				$UserDetail['device'] 				= ($userD->device) ? $userD->device : '';
				$UserDetail['deviceToken'] 			= ($userD->deviceToken) ? $userD->deviceToken : '';
				$UserDetail['firebase_id'] 			= ($userD->firebase_id) ? $userD->firebase_id : '';
				$UserDetail['socialId'] 			= ($userD->socialId) ? $userD->socialId : '';
				$UserDetail['socialType'] 			= ($userD->socialType) ? $userD->socialType : '';
				
				// $ExpiryDate = $userD->ExpiryDate ?? '';
				// $UserDetail['ExpiryStatus'] 		= (date('Y-m-d', strtotime($ExpiryDate)) > date('Y-m-d')) ? 1 : 0; //0=EXPIRED
				// $UserDetail['ExpiryDate'] 			= ($userD->ExpiryDate) ? date('Y-m-d', strtotime($userD->ExpiryDate)) : '';
				// $UserDetail['subscribeDate'] 		= ($userD->subscribeDate) ? date('Y-m-d H:i:s', strtotime($userD->subscribeDate)) :'';
				// $UserDetail['stripeSubscriptionId'] = ($userD->stripeSubscriptionId) ? $userD->stripeSubscriptionId :'';
				
				$UserDetail['total_Post'] = $postTable->find()->where(['Posts.userId'=>$userD->id])->count();
				$UserDetail['total_fnd'] = $friendTable->find()->where(['Friends.status'=>2,'OR'=>[
									['Friends.senderId'=>$userD->id],
									['Friends.receiverId'=>$userD->id]
								]])->count();
				$UserDetail['total_profile_liked'] = $profilelikesTable->find()->where(['Profilelikes.profileId'=>$userD->id, 'Profilelikes.status'=>1])->count();
				
				$UserDetail['you_liked_profile'] = 0;
				$UserDetail['he_liked_profile'] = 0;
				$UserDetail['fnd_status'] = "";
				if($OPID !=""){
					$UserDetail['you_liked_profile'] = $profilelikesTable->find()->where(['Profilelikes.profileId'=>$OPID, 'Profilelikes.status'=>1,'Profilelikes.userId'=>$userID])->count();
					$UserDetail['he_liked_profile'] = $profilelikesTable->find()->where(['Profilelikes.profileId'=>$userID, 'Profilelikes.status'=>1,'Profilelikes.userId'=>$OPID])->count();
					$UserDetail['fnd_status'] = $friendTable->find()->select(['requestId'=>'Friends.id','status'=>'Friends.status','senderId'=>'Friends.senderId','receiverId'=>'Friends.receiverId'])->where(['OR'=>[
									['Friends.senderId'=>$userID,'Friends.receiverId'=>$userD->id],
									['Friends.senderId'=>$userD->id,'Friends.receiverId'=>$userID]
								]])->first() ?? "";
				}
				
				$UserDetail['P_S_Profile'] = 1;
				$UserDetail['P_S_Post'] = 2;
				$UserDetail['P_S_Friends'] = 3;
				$UserDetail['P_S_Profile_picture'] = 2;

				$response['status'] = 'success';
				$response['data'] = $UserDetail;
				
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'UserId required';
			}
			
			return  $response;
	}
	

	private function profilelike(){
		$TableLikes = $this->fetchTable('Profilelikes');
		$UsersTable = $this->fetchTable('Users');
		$SettingsTable = $this->fetchTable('Settings');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$postID = $requestData['profileId'] ?? null;
		$status = $requestData['status'] ?? null; //1=Like 2=Dislike, 0=unlkine

		if (empty($userId) || empty($postID) || empty($status)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId, status and profileId are required.'
			];
		}
		$likes = $TableLikes->find()->where(['Profilelikes.profileId'=>$postID,'Profilelikes.userId'=>$userId])->first();
		$likeData = [
			'userId' => $userId,
			'profileId' => $postID,
			'status' => $status,
		];
		
		if(@$likes->id == ""){
			$likes = $TableLikes->newEntity($likeData, ['validate' => false]);
		}
		if ($TableLikes->save($likes)) {
			$checkliked = $TableLikes->find()->where(['Profilelikes.userId'=>$postID,'Profilelikes.profileId'=>$userId, 'Profilelikes.status'=>1])->first();
			
			$sender_d = $UsersTable->find()->where(['Users.id'=>$userId])->first();
			$receiver_d = $UsersTable->find()->where(['Users.id'=>$postID])->first();
			$Settings = $SettingsTable->find()->where(['Settings.userId'=>$postID])->first();

			$notification_data = [
				'userId' => $receiver_d->id,
				'message'=> $sender_d->firstName.' like your profile',
				'status' => 1,
				'postId' => $receiver_d->id,
				'type_of' => 'Profile',
				'created'=> date('Y-m-d H:i:s')
			];
			$this->notificationsave($notification_data);
			$bodyU=[
				'message'=> $sender_d->firstName.' like your profile',
				'type' => 'like_profile'
			];
			if(@$receiver_d->deviceToken){
				if(@$Settings->N_S_like_profile == 1){
					$this->sendNotificationOnAndroid(@$receiver_d->deviceToken,$bodyU);
				}
			}
			
			if(@$checkliked->id !="" && $status == 1){
				return [
					'status' => 'success',
					'msg' => 'Profile liked successfully.',
					'bothlike' =>'yes'
				];
			}else{
				return [
					'status' => 'success',
					'msg' => 'Profile liked successfully.',
					'bothlike' =>'no'
				];
			}
			
			
		}
	}

	private function countrylist(){
		$parentCountryList = $this->fetchTable('Countries')->find()->order(['Countries.name'=>'ASC'])->toArray();
																					//->where(['Categories.status'=>'0'])
			
		$PC = array();
		foreach($parentCountryList as $key=>$VV){
			$PC['id'] = $VV->id;
			$PC['name'] = $VV->name;
			$PC['phonecode'] = '+'.$VV->phonecode;
			$list[] = $PC;
		}
		$response['status'] = "success";
		$response['data'] = $list;
		return $response;
	}
		
	private function statelist($counttyId = NULL){
		
		$counttyID = ($counttyId) ? $counttyId : @$this->request->getData('counttyId');
		if(@$this->request->getData['state_name'] !=''){
			$parentCountryList = $this->fetchTable('States')->find()->where(['States.country_id'=>$counttyID,'States.name Like'=>$this->request->getData['state_name'].'%'])->order(['States.name'=>'ASC'])->toArray();
		}else{
			$parentCountryList = $this->fetchTable('States')->find()->where(['States.country_id'=>$counttyID])->order(['States.name'=>'ASC'])->toArray();
		}
		
		$PC = array();
		foreach($parentCountryList as $key=>$VV){
			$PC['id'] = $VV->id;
			$PC['name'] = $VV->name;
			$PC['state_tax'] = $VV->state_tax;
			$list[] = $PC;
		}
		$response['status'] = "success";
		$response['data'] = $list;
		return $response;
		//return  $parentCountryList;
	}

	function citylist($counttyId = NULL){
		//$this->loadModel('Cities');
		$counttyID = ($counttyId) ? $counttyId : @$this->request->getData('state_id');
		$parentCountryList = $this->fetchTable('Cities')->find()->where(['Cities.state_id'=>$counttyID])->order(['Cities.name'=>'ASC'])->toArray();
		$PC = array();
		foreach($parentCountryList as $key=>$VV){
			$PC['id'] = $VV->id;
			$PC['name'] = $VV->name;
			$list[] = $PC;
		}
		$response['status'] = "success";
		$response['data'] = $list;
		return $response;
		//return  $parentCountryList;
	}
	
	private function userlist($userId=NULL){
		$TableUsers = $this->fetchTable('Users');
		$FriendsTable = $this->fetchTable('Friends');
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Users.id' => 'desc'],
				'page'=>$pageNo,
			];
			$keyword = $this->request->getData('keyword');
			$city = $this->request->getData('city');
			$frmdcondition [] = ['OR'=>[
									['Friends.senderId'=>$VendorID],
									['Friends.receiverId'=>$VendorID]
								]
							];
			
			$fndlist = $FriendsTable->find()->where($frmdcondition)->toArray();
			$friends[] = $VendorID;
			foreach($fndlist as $fnd){
				if($fnd->senderId == $VendorID){
					$friends[] = $fnd->receiverId;
				}else{
					$friends[] = $fnd->senderId;
				}
			}
			//pr($friends); die;
			$condition[] = ['Users.id NOT IN'=>$friends,'Users.role'=>'Member', 'Users.status'=>1];
			
			if(@$keyword !=''){
				$condition[] = ['OR'=>[
					['Users.firstName Like'=>$keyword.'%'],
					['Users.cityname Like'=>$keyword.'%'],
				]];
			}

			if(@$city !=''){
				$condition[] = ['OR'=>[
					['Users.cityname Like'=>$city.'%'],
				]];
			}
			
			
			$query = $TableUsers->find()->where($condition);
			$totalCount =  $query->count();
			$userservices =array();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['userId'] 			= $val->id ?? '';
				$Darray[$i]['firstName'] 		= $val->firstName ?? '';
				$Darray[$i]['email'] 			= $val->email ?? '';
				$Darray[$i]['contactNumber'] 	= $val->contactNumber ?? '';
				$Darray[$i]['cityname'] 		= $val->cityname ?? '';
				$Darray[$i]['device'] 			= $val->device ?? '';
				$Darray[$i]['deviceToken'] 		= $val->deviceToken ?? '';
				$Darray[$i]['dob'] 				= $val->dob ?? '';
				$Darray[$i]['gender'] 			= $val->gender ?? '';
				$Darray[$i]['BImage'] 			= ($val->BImage) ? $this->profilePath.$val->BImage : '';
				$Darray[$i]['profile_picture'] 	= ($val->profile_picture) ? $this->profilePath.$val->profile_picture : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	private function frinedrequest(){
		/*action:frinedrequest
		senderId:2
		receiverId:3
		status:1 */
		$FriendsTable = $this->fetchTable('Friends');
		$UsersTable = $this->fetchTable('Users');
		$SettingsTable = $this->fetchTable('Settings');

		$requestData = $this->request->getData();
		$senderId = $requestData['senderId'] ?? null;
		$recerverId = $requestData['receiverId'] ?? null;
		$status = $requestData['status'] ?? null;

		if (empty($senderId) || empty($recerverId) || $status != 1) {
			return [
				'status' => 'Fails',
				'msg' => 'senderId, status and receiverid are required.'
			];
		}
		$postData = [
			'senderId' => $senderId,
			'receiverId' => $recerverId,
			'status' => $status,
			'created' 	=> date('Y-m-d H:i:s'),
		];
		$friends = $FriendsTable->find()->where(['OR'=>[
														['Friends.senderId'=>$senderId,'Friends.receiverId'=>$recerverId],
														['Friends.senderId'=>$recerverId,'Friends.receiverId'=>$senderId]
													]
												])->first();
		if(@$friends->id !=''){
			return [
				'status' => 'Fails',
				'msg' => 'Request already sent.'
			];
		}
		$friends = $FriendsTable->newEntity($postData, ['validate' => false]);
		if ($FriendsTable->save($friends)) {
			$response['status'] = "success";
			$response['msg'] = 'Request sent successfully.';
			$sender_d = $UsersTable->find()->where(['Users.id'=>$friends->senderId])->first();
			$receiver_d = $UsersTable->find()->where(['Users.id'=>$friends->receiverId])->first();
			$Settings = $SettingsTable->find()->where(['Settings.userId'=>$friends->receiverId])->first();
			$notification_data = [
				'userId' => $receiver_d->id,
				'message'=> $sender_d->firstName.' sent you a friend request',
				'status' => 1,
				'type_of' => 'Friend',
				'created'=> date('Y-m-d H:i:s')
			];
			$this->notificationsave($notification_data);
			$bodyU=[
				'message'=> $sender_d->firstName.' sent you a friend request',
				'type' => 'Decline'
			];
			if(@$receiver_d->deviceToken){
				if(@$Settings->N_S_Friend_request == 1){
					$this->sendNotificationOnAndroid(@$receiver_d->deviceToken,$bodyU);
				}
			}
			if(@$Settings->E_S_Friend_request == 1){
				$subject = "Freind Request by".$sender_d->firstName." LGBT-TOGO!";
				$message = "Dear ".$receiver_d->firstName;
				$message .= "<br>";
				$message .= "<br>".$sender_d->firstName.' sent you a friend request';
				$message .= "<br>";
				$message .= "<br> Thanks";
				$this->phpemail($receiver_d->email, $subject, $message);
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'Invalid request';
		}
		return  $response;
	}

	private function frinedacceptdecline(){
		/*action:frinedacceptdecline
		requestId:1
		userId:3
		status:3/2 */
		$FriendsTable = $this->fetchTable('Friends');
		$UsersTable = $this->fetchTable('Users');
		$SettingsTable = $this->fetchTable('Settings');

		$requestData = $this->request->getData();
		$friendID = $requestData['requestId'] ?? null;  //Request Id
		$userId = $requestData['userId'] ?? null; //login user id
		$status = $requestData['status'] ?? null;

		if (empty($friendID) || empty($userId) || $status == 1) {
			return [
				'status' => 'Fails',
				'msg' => 'userid, status and requestid are required.'
			];
		}
		$friends = $FriendsTable->find()->where(['Friends.id'=>$friendID,'Friends.receiverId'=>$userId])->first();
		if(@$friends->id ==''){
			return [
				'status' => 'Fails',
				'msg' => 'Invalid request data.'
			];
		}
		$sender_d = $UsersTable->find()->where(['Users.id'=>$friends->senderId])->first();
		$receiver_d = $UsersTable->find()->where(['Users.id'=>$friends->receiverId])->first();
		$Settings = $SettingsTable->find()->where(['Settings.userId'=>$friends->senderId])->first();
		if($status == 2 ){
			$friends->status = $status; 
			$FriendsTable->save($friends);
			$response['status'] = "success";
			$response['msg'] = 'Request accepted successfully.';
			
			$notification_data = [
				'userId' => $sender_d->id,
				'message'=> $receiver_d->firstName.' Accept your friend request',
				'status' => 1,
				'type_of' => 'Friend',
				'created'=> date('Y-m-d H:i:s')
			];
			$this->notificationsave($notification_data);
			$bodyU=[
				'message'=> $receiver_d->firstName.' Accept your friend request',
				'type' => 'accept'
			];
			if(@$sender_d->deviceToken){
				if(@$Settings->N_S_Friend_accept == 1){
					$this->sendNotificationOnAndroid(@$sender_d->deviceToken,$bodyU);
				}
			}
			if(@$Settings->E_S_Friend_request == 1){
				$subject = "Freind Accept by ".$receiver_d->firstName." LGBT-TOGO!";
				$message = "Dear ".$sender_d->firstName;
				$message .= "<br>";
				$message .= "<br>".$receiver_d->firstName.' Accept your friend request';
				$message .= "<br>";
				$message .= "<br> Thanks";
				$this->phpemail($sender_d->email, $subject, $message);
			}
		}elseif($status == 4 ){
			$friends->status = $status; 
			$FriendsTable->save($friends);
			$response['status'] = "success";
			$response['msg'] = 'Friend Bloked successfully.';
			
		}else{
			$FriendsTable->delete($friends);
			$response['status'] = "success";
			$response['msg'] = 'Request declined successfully.';

			$notification_data = [
				'userId' => $sender_d->id,
				'message'=> $receiver_d->firstName.' Decline your friend request',
				'status' => 1,
				'created'=> date('Y-m-d H:i:s')
			];
			$this->notificationsave($notification_data);
			$bodyU=[
				'message'=> $receiver_d->firstName.' Decline your friend request',
				'type' => 'Decline'
			];
			if(@$sender_d->deviceToken){
				if(@$Settings->N_S_Friend_accept == 1){
					$this->sendNotificationOnAndroid(@$sender_d->deviceToken,$bodyU);
				}
			}
		}
		return  $response;
	}

	private function friendlist($userId=NULL){
		/* action:friendlist
			userId:3
			status: optional */
		$FriendsTable = $this->fetchTable('Friends');
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$status = $this->request->getData('status') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !=''){
			
			if($status){
				$condition[] = ['Friends.status'=>$status];
			}

			$condition [] = ['OR'=>[
									['Friends.senderId'=>$VendorID],
									['Friends.receiverId'=>$VendorID]
								]
							];
			
			$userservices = $FriendsTable->find()->where($condition)->contain(['Senders','Receivers'])->toArray();
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['requestId'] 		= $val->id ?? '';
				$Darray[$i]['senderId'] 		= $val->senderId ?? '';
				$Darray[$i]['receiverId'] 		= $val->receiverId ?? '';
				$Darray[$i]['status'] 			= $val->status ?? '';
				$Darray[$i]['block_by_sender'] 	= $val->block_by_sender ?? '';
				$Darray[$i]['block_by_receiver'] = $val->block_by_receiver ?? '';
				
				$Darray[$i]['Sender']['firstName'] 		= $val->sender->firstName ?? '';
				$Darray[$i]['Sender']['email'] 			= $val->sender->email ?? '';
				$Darray[$i]['Sender']['contactNumber'] 	= $val->sender->contactNumber ?? '';
				$Darray[$i]['Sender']['dob'] 			= $val->sender->dob ?? '';
				$Darray[$i]['Sender']['gender'] 		= $val->sender->gender ?? '';
				$Darray[$i]['Sender']['firebase_id'] 	= $val->sender->firebase_id ?? '';
				$Darray[$i]['Sender']['BImage'] 		= (@$val->sender->BImage) ? $this->profilePath.$val->sender->BImage : '';
				$Darray[$i]['Sender']['profile_picture']= (@$val->sender->profile_picture) ? $this->profilePath.$val->sender->profile_picture : '';

				$Darray[$i]['Receiver']['firstName'] 		= $val->receiver->firstName ?? '';
				$Darray[$i]['Receiver']['email'] 			= $val->receiver->email ?? '';
				$Darray[$i]['Receiver']['contactNumber'] 	= $val->receiver->contactNumber ?? '';
				$Darray[$i]['Receiver']['dob'] 				= $val->receiver->dob ?? '';
				$Darray[$i]['Receiver']['gender'] 			= $val->receiver->gender ?? '';
				$Darray[$i]['Receiver']['firebase_id'] 		= $val->receiver->firebase_id ?? '';
				$Darray[$i]['Receiver']['BImage'] 			= (@$val->receiver->BImage) ? $this->profilePath.$val->receiver->BImage : '';
				$Darray[$i]['Receiver']['profile_picture'] 	= (@$val->receiver->profile_picture) ? $this->profilePath.$val->receiver->profile_picture : '';

				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	private function friendblocklist($userId=NULL){
		/* action:friendlist
			userId:3
			status: optional */
		$FriendsTable = $this->fetchTable('Friends');
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$status = $this->request->getData('status') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !=''){
			
			
			$condition[] = ['Friends.status'=>2];
			$condition [] = ['OR'=>[
									['Friends.senderId'=>$VendorID],
									['Friends.receiverId'=>$VendorID]
								]
							];
			
			$userservices = $FriendsTable->find()->where($condition)->contain(['Senders','Receivers'])->toArray();
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				if(($val->senderId == $VendorID && $val->block_by_sender == 1) || ($val->receiverId == $VendorID && $val->block_by_receiver == 1)){
					$Darray[$i]['requestId'] 		= $val->id ?? '';
					$Darray[$i]['senderId'] 		= $val->senderId ?? '';
					$Darray[$i]['receiverId'] 		= $val->receiverId ?? '';
					$Darray[$i]['status'] 			= $val->status ?? '';
					$Darray[$i]['block_by_sender'] 	= $val->block_by_sender ?? '';
					$Darray[$i]['block_by_receiver'] = $val->block_by_receiver ?? '';
					
					$Darray[$i]['Sender']['firstName'] 		= $val->sender->firstName ?? '';
					$Darray[$i]['Sender']['email'] 			= $val->sender->email ?? '';
					$Darray[$i]['Sender']['contactNumber'] 	= $val->sender->contactNumber ?? '';
					$Darray[$i]['Sender']['dob'] 			= $val->sender->dob ?? '';
					$Darray[$i]['Sender']['gender'] 		= $val->sender->gender ?? '';
					$Darray[$i]['Sender']['BImage'] 		= (@$val->sender->BImage) ? $this->profilePath.$val->sender->BImage : '';
					$Darray[$i]['Sender']['profile_picture']= (@$val->sender->profile_picture) ? $this->profilePath.$val->sender->profile_picture : '';

					$Darray[$i]['Receiver']['firstName'] 		= $val->receiver->firstName ?? '';
					$Darray[$i]['Receiver']['email'] 			= $val->receiver->email ?? '';
					$Darray[$i]['Receiver']['contactNumber'] 	= $val->receiver->contactNumber ?? '';
					$Darray[$i]['Receiver']['dob'] 				= $val->receiver->dob ?? '';
					$Darray[$i]['Receiver']['gender'] 			= $val->receiver->gender ?? '';
					$Darray[$i]['Receiver']['BImage'] 			= (@$val->receiver->BImage) ? $this->profilePath.$val->receiver->BImage : '';
					$Darray[$i]['Receiver']['profile_picture'] 	= (@$val->receiver->profile_picture) ? $this->profilePath.$val->receiver->profile_picture : '';

					$i++;
				}
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	private function postadd(){
		$PostTable = $this->fetchTable('Posts');
		//$UsersTable = $this->fetchTable('Users');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$postTitle = $requestData['postTitle'] ?? null;

		if (empty($userId)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId and postTitle are required.'
			];
		}
		$fileName1 = '';
		if(@$_FILES['image_1']['name'] !='' && $_FILES['image_1']['error'] == 0){
			$fileName1=time().stripslashes($_FILES['image_1']['name']);
			$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
			$rplc =['','',"","","","","","","","","","","","",""];
			$fileName1 = str_replace($str,$rplc,$fileName1);
		}
		$fileName2 = '';
		if(@$_FILES['image_2']['name'] !='' && $_FILES['image_2']['error'] == 0){
			$fileName2=time().stripslashes($_FILES['image_2']['name']);
			$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
			$rplc =['','',"","","","","","","","","","","","",""];
			$fileName2 = str_replace($str,$rplc,$fileName2);
		}
		$fileName3 = '';
		if(@$_FILES['image_3']['name'] !='' && $_FILES['image_3']['error'] == 0){
			$fileName3=time().stripslashes($_FILES['image_3']['name']);
			$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
			$rplc =['','',"","","","","","","","","","","","",""];
			$fileName3 = str_replace($str,$rplc,$fileName3);
		}
		$fileName4 = '';
		if(@$_FILES['image_4']['name'] !='' && $_FILES['image_4']['error'] == 0){
			$fileName4=time().stripslashes($_FILES['image_4']['name']);
			$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
			$rplc =['','',"","","","","","","","","","","","",""];
			$fileName4 = str_replace($str,$rplc,$fileName4);
		}
		$fileName5 = '';
		if(@$_FILES['image_5']['name'] !='' && $_FILES['image_5']['error'] == 0){
			$fileName5=time().stripslashes($_FILES['image_5']['name']);
			$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
			$rplc =['','',"","","","","","","","","","","","",""];
			$fileName5 = str_replace($str,$rplc,$fileName5);
		}
		$video = '';
		if(@$_FILES['video']['name'] !='' && $_FILES['video']['error'] == 0){
			$video=time().stripslashes($_FILES['video']['name']);
			$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
			$rplc =['','',"","","","","","","","","","","","",""];
			$video = str_replace($str,$rplc,$video);
		}
		$postData = [
			'postTitle' => $postTitle,
			'userId' 	=> $userId,
			'description' => $requestData['description'] ?? "",
			'tag' 		=> $requestData['tag'] ?? "",
			'postType' 	=> $requestData['postType'] ?? "Text",
			'image_1' 	=> $fileName1 ?? "",
			'image_2' 	=> $fileName2 ?? "",
			'image_3' 	=> $fileName3 ?? "",
			'image_4' 	=> $fileName4 ?? "",
			'image_5' 	=> $fileName5 ?? "",
			'video' 	=> $video ?? "",
			'created' 	=> date('Y-m-d H:i:s'),
			'status' 	=> 1,
		];

		$posts = $PostTable->newEntity($postData, ['validate' => false]);
		if ($PostTable->save($posts)) {
			$uploadDir = WWW_ROOT.'/img/uploads/post/' . $userId . '/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true); // recursive
			}
			if(@$_FILES['image_1']['name'] !='' && $fileName1 !=''){
				$target = $uploadDir.$fileName1;
				$source=$_FILES['image_1']['tmp_name'];
				move_uploaded_file($source,$target);
			}
			if(@$_FILES['image_2']['name'] !='' && $fileName2 !=''){
				$target = $uploadDir.$fileName2;
				$source=$_FILES['image_2']['tmp_name'];
				move_uploaded_file($source,$target);
			}
			if(@$_FILES['image_3']['name'] !='' && $fileName3 !=''){
				$target = $uploadDir.$fileName3;
				$source=$_FILES['image_3']['tmp_name'];
				move_uploaded_file($source,$target);
			}
			if(@$_FILES['image_4']['name'] !='' && $fileName4 !=''){
				$target = $uploadDir.$fileName4;
				$source=$_FILES['image_4']['tmp_name'];
				move_uploaded_file($source,$target);
			}
			if(@$_FILES['image_5']['name'] !='' && $fileName5 !=''){
				$target = $uploadDir.$fileName5;
				$source=$_FILES['image_5']['tmp_name'];
				move_uploaded_file($source,$target);
			}
			if(@$_FILES['video']['name'] !='' && $video !=''){
				$target = $uploadDir.$video;
				$source=$_FILES['video']['tmp_name'];
				move_uploaded_file($source,$target);
			}
		}
		
		return [
			'status' => 'success',
			'msg' => 'Post added successfully.'
		];
	}

	function postlist($userId=NULL){
		$TablePosts = $this->fetchTable('Posts');
		$TableLikes = $this->fetchTable('Likes');
		
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$TYPE = $this->request->getData('type') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Posts.id' => 'desc'],
				'page'=>$pageNo,
			];
			$keyword = $this->request->getData('keyword');
			
			if($TYPE == 'own'){
				$condition[] = ['Posts.userId'=>$VendorID];
			}else{
				$condition[] = ['Posts.status'=>1];
			}
			if(@$keyword !=''){
				$condition[] = ['OR'=>[
					['Users.firstName Like'=>$keyword.'%'],
					['Users.email Like'=>$keyword.'%'],
					['Users.phone Like'=>$keyword.'%']
				]];
			}
			
			$query = $TablePosts->find()->where($condition)->contain(['Users']);
			$totalCount =  $query->count();
			$userservices =array();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['postId'] 		= $val->id ?? '';
				$Darray[$i]['userId'] 		= $val->userId ?? '';
				$Darray[$i]['postTitle'] 	= $val->postTitle ?? '';
				$Darray[$i]['description'] 	= $val->description ?? '';
				$Darray[$i]['postType'] 	= $val->postType ?? '';
				$Darray[$i]['image_1'] 		= ($val->image_1) ? $this->postPath.$val->userId.'/'.$val->image_1 : '';
				$Darray[$i]['image_2'] 		= ($val->image_2) ? $this->postPath.$val->userId.'/'.$val->image_2 : '';
				$Darray[$i]['image_3'] 		= ($val->image_3) ? $this->postPath.$val->userId.'/'.$val->image_3 : '';
				$Darray[$i]['image_4'] 		= ($val->image_4) ? $this->postPath.$val->userId.'/'.$val->image_4 : '';
				$Darray[$i]['image_5'] 		= ($val->image_5) ? $this->postPath.$val->userId.'/'.$val->image_5 : '';
				$Darray[$i]['video'] 		= ($val->video) ? 	$this->postPath.$val->userId.'/'.$val->video : '';
				
				$Darray[$i]['user']['firstName'] 		= $val->user->firstName ?? '';
				$Darray[$i]['user']['email'] 			= $val->user->email ?? '';
				$Darray[$i]['user']['contactNumber'] 	= $val->user->contactNumber ?? '';
				$Darray[$i]['user']['firebase_id'] 		= $val->user->firebase_id ?? '';
				$Darray[$i]['user']['BImage'] 			= (@$val->user->BImage) ? $this->profilePath.$val->user->BImage : '';
				$Darray[$i]['user']['profile_picture'] 	= (@$val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';

				$Darray[$i]['tag'] 			= $val->tag ?? ''; 
				$Darray[$i]['created'] 		= $val->created ?? '';
				$Darray[$i]['totalComment'] = $val->totalComment ?? '';
				$Darray[$i]['totalLike'] 	= $val->totalLike ?? '';
				$Darray[$i]['youliked'] 	= $TableLikes->find()->where(['Likes.postId'=>$val->id, 'Likes.userId'=>$VendorID, 'Likes.status'=>1])->count();
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	function postlistfnd($userId=NULL){
		$TablePosts = $this->fetchTable('Posts');
		$TableLikes = $this->fetchTable('Likes');
		
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$fnd_id = $this->request->getData('friend_user_id') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !='' && $fnd_id !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Posts.id' => 'desc'],
				'page'=>$pageNo,
			];
			$keyword = $this->request->getData('keyword');
			$condition[] = ['Posts.userId'=>$fnd_id, 'Posts.status'=>1];
			
			if(@$keyword !=''){
				$condition[] = ['OR'=>[
					['Users.firstName Like'=>$keyword.'%'],
					['Users.email Like'=>$keyword.'%'],
					['Users.phone Like'=>$keyword.'%']
				]];
			}
			
			$query = $TablePosts->find()->where($condition)->contain(['Users']);
			$totalCount =  $query->count();
			$userservices =array();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['postId'] 		= $val->id ?? '';
				$Darray[$i]['userId'] 		= $val->userId ?? '';
				$Darray[$i]['postTitle'] 	= $val->postTitle ?? '';
				$Darray[$i]['description'] 	= $val->description ?? '';
				$Darray[$i]['image_1'] 		= ($val->image_1) ? $this->postPath.$val->userId.'/'.$val->image_1 : '';
				$Darray[$i]['image_2'] 		= ($val->image_2) ? $this->postPath.$val->userId.'/'.$val->image_2 : '';
				$Darray[$i]['image_3'] 		= ($val->image_3) ? $this->postPath.$val->userId.'/'.$val->image_3 : '';
				$Darray[$i]['image_4'] 		= ($val->image_4) ? $this->postPath.$val->userId.'/'.$val->image_4 : '';
				$Darray[$i]['image_5'] 		= ($val->image_5) ? $this->postPath.$val->userId.'/'.$val->image_5 : '';
				$Darray[$i]['video'] 		= ($val->video) ? 	$this->postPath.$val->userId.'/'.$val->video : '';
				
				$Darray[$i]['user']['firstName'] 		= $val->user->firstName ?? '';
				$Darray[$i]['user']['email'] 			= $val->user->email ?? '';
				$Darray[$i]['user']['contactNumber'] 	= $val->user->contactNumber ?? '';
				$Darray[$i]['user']['BImage'] 			= (@$val->user->BImage) ? $this->profilePath.$val->user->BImage : '';
				$Darray[$i]['user']['profile_picture'] 	= (@$val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';

				$Darray[$i]['tag'] 			= $val->tag ?? ''; 
				$Darray[$i]['created'] 		= $val->created ?? '';
				$Darray[$i]['totalComment'] = $val->totalComment ?? '';
				$Darray[$i]['totalLike'] 	= $val->totalLike ?? '';
				$Darray[$i]['youliked'] 	= $TableLikes->find()->where(['Likes.postId'=>$val->id, 'Likes.userId'=>$VendorID, 'Likes.status'=>1])->count();
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId and fnd id is required.';
		}
		return  $response;
	}

	function postdetail($userId=NULL){
		$TablePosts = $this->fetchTable('Posts');
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$postID = ($userId)? $userId : $this->request->getData('postId');
		if($VendorID !=''){
			$condition[] = ['Posts.id'=>$postID];
			$val = $TablePosts->find()->where($condition)->contain(['Users'])->first();
			$Darray['postId'] 		= $val->id ?? '';
			$Darray['userId'] 		= $val->userId ?? '';
			$Darray['postTitle'] 	= $val->postTitle ?? '';
			$Darray['description'] 	= $val->description ?? '';
			$Darray['image_1'] 		= ($val->image_1) ? $this->postPath.$val->userId.'/'.$val->image_1 : '';
			$Darray['image_2'] 		= ($val->image_2) ? $this->postPath.$val->userId.'/'.$val->image_2 : '';
			$Darray['image_3'] 		= ($val->image_3) ? $this->postPath.$val->userId.'/'.$val->image_3 : '';
			$Darray['image_4'] 		= ($val->image_4) ? $this->postPath.$val->userId.'/'.$val->image_4 : '';
			$Darray['image_5'] 		= ($val->image_5) ? $this->postPath.$val->userId.'/'.$val->image_5 : '';
			$Darray['video'] 		= ($val->video) ? 	$this->postPath.$val->userId.'/'.$val->video : '';

			$Darray['user']['firstName'] 		= $val->user->firstName ?? '';
			$Darray['user']['email'] 			= $val->user->email ?? '';
			$Darray['user']['contactNumber'] 	= $val->user->contactNumber ?? '';
			$Darray['user']['BImage'] 			= (@$val->user->BImage) ? $this->profilePath.$val->user->BImage : '';
			$Darray['user']['profile_picture'] 	= (@$val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';

			$Darray['tag'] 			= $val->tag ?? '';
			$Darray['created'] 		= $val->created ?? '';
			$Darray['totalComment'] = $val->totalComment ?? '';
			$Darray['totalLike'] 	= $val->totalLike ?? '';
			
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	function postdelete($userId=NULL){
		$TablePosts = $this->fetchTable('Posts');
		$TableLikes = $this->fetchTable('Likes');
		$TableComments = $this->fetchTable('Comments');
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$postID = ($userId)? $userId : $this->request->getData('postId');
		if($VendorID !=''){
			$condition[] = ['Posts.id'=>$postID];
			$val = $TablePosts->find()->where($condition)->contain(['Users'])->first();
			if(empty($val)){
				$response['status'] = 'Fails';
				$response['msg'] = 'Post no longer available.';
			}else{
				$TableLikes->deleteAll(['Likes.postId'=>$postID]);
				$TableComments->deleteAll(['Comments.postId'=>$postID]);
				$folder1 = WWW_ROOT.'/img/uploads/post/' . $val->userId . '/'.$val->image_1;
				$folder2 = WWW_ROOT.'/img/uploads/post/' . $val->userId . '/'.$val->image_2;
				$folder3 = WWW_ROOT.'/img/uploads/post/' . $val->userId . '/'.$val->image_3;
				$folder4 = WWW_ROOT.'/img/uploads/post/' . $val->userId . '/'.$val->image_4;
				$folder5 = WWW_ROOT.'/img/uploads/post/' . $val->userId . '/'.$val->image_5;
				$video = WWW_ROOT.'/img/uploads/post/' . $val->userId . '/'.$val->video;
				
				@unlink($folder1);
				@unlink($folder2);
				@unlink($folder3);
				@unlink($folder4);
				@unlink($folder5);@unlink($video);
				$TablePosts->delete($val);

				$response['status'] = "success";
				$response['msg'] = 'Post deleted successfully.';
			}
			
			
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	private function postlike(){
		$PostTable = $this->fetchTable('Posts');
		$TableLikes = $this->fetchTable('Likes');
		$TableUsers = $this->fetchTable('Users');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$postID = $requestData['postId'] ?? null;
		$status = $requestData['status'] ?? null; //1=Like 2=Dislike, 0=unlkine

		if (empty($userId) || empty($postID) || empty($status)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId, status and post_id are required.'
			];
		}
		$likes = $TableLikes->find()->where(['Likes.postId'=>$postID,'Likes.userId'=>$userId])->first();
		$likeData = [
			'userId' => $userId,
			'postId' => $postID,
			'status' => $status,
		];
		
		$count = ($status == 1) ? 1 : -1;
		
		if(@$likes->id == ""){
			$likes = $TableLikes->newEntity($likeData, ['validate' => false]);
		}else{
			$count = ($likes->status == $status ) ? 0 : (($status == 1) ? 1 : -1);
			$likes->status = $status;
		}
		if ($TableLikes->save($likes)) {
			$posts = $PostTable->find()->where(['Posts.id'=>$postID])->first();
			$posts->totalLike = ($posts->totalLike == 0 && $count == -1 ) ? 0 : $posts->totalLike +=$count;
			$PostTable->save($posts);

			if($status == 1){
				$post_user = $TableUsers->find()->where(['Users.id'=>$posts->userId])->first();
				$comment_user = $TableUsers->find()->where(['Users.id'=>$userId])->first();
				$notification_data = [
					'userId' => $posts->userId,
					'message'=> $comment_user->firstName.' liked your post.',
					'status' => 1,
					'postId' => $posts->id,
					'type_of' => 'Like',
					'created'=> date('Y-m-d H:i:s')
				];
				$this->notificationsave($notification_data);
				$bodyU=[
					'message'=> $comment_user->firstName.' liked your post.',
					'type' => 'postlike'
				];
				if(@$post_user->deviceToken){
					$this->sendNotificationOnAndroid(@$post_user->deviceToken,$bodyU);
				}
				
			}

			return [
				'status' => 'success',
				'msg' => 'Post liked successfully.'
			];
		}
	}

	function likelist($postId=NULL, $userId=NULL){
		$TableLikes = $this->fetchTable('Likes');
		
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$PostID = ($postId)? $postId : $this->request->getData('postId');
		$status = $this->request->getData('stataus') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($PostID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Likes.id' => 'desc'],
				'page'=>$pageNo,
			];
			//$keyword = $this->request->getData('keyword');
			
			if($status !==""){
				$condition[] = ['Likes.status IN'=>explode(",", $status)];
			}
			$condition[] = ['Likes.postId'=>$PostID];
			
			$query = $TableLikes->find()->where($condition)->contain(['Users','Posts']);
			$totalCount =  $query->count();
			$userservices =array();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['likeid'] 		= $val->id ?? '';
				$Darray[$i]['userId'] 		= $val->userId ?? '';
				$Darray[$i]['postId'] 		= $val->postId ?? '';
				$Darray[$i]['stataus'] 		= $val->status ?? 0;
				$Darray[$i]['created'] 		= $val->created ?? '';

				$Darray[$i]['post']['postTitle'] 	= $val->post->postTitle ?? '';
				$Darray[$i]['post']['description'] 	= $val->post->description ?? '';
				$Darray[$i]['post']['image_1'] 		= ($val->post->image_1) ? $this->postPath.$val->post->userId.'/'.$val->post->image_1 : '';
				$Darray[$i]['post']['image_2'] 		= ($val->post->image_2) ? $this->postPath.$val->post->userId.'/'.$val->post->image_2 : '';
				$Darray[$i]['post']['image_3'] 		= ($val->post->image_3) ? $this->postPath.$val->post->userId.'/'.$val->post->image_3 : '';
				$Darray[$i]['post']['image_4'] 		= ($val->post->image_4) ? $this->postPath.$val->post->userId.'/'.$val->post->image_4 : '';
				$Darray[$i]['post']['image_5'] 		= ($val->post->image_5) ? $this->postPath.$val->post->userId.'/'.$val->post->image_5 : '';
				$Darray[$i]['post']['video'] 		= ($val->post->video) ? $this->postPath.$val->post->userId.'/'.$val->post->video : '';
				
				$Darray[$i]['user']['firstName'] 		= $val->user->firstName ?? '';
				$Darray[$i]['user']['email'] 			= $val->user->email ?? '';
				$Darray[$i]['user']['contactNumber'] 	= $val->user->contactNumber ?? '';
				$Darray[$i]['user']['BImage'] 			= (@$val->user->BImage) ? $this->profilePath.$val->user->BImage : '';
				$Darray[$i]['user']['profile_picture'] 	= (@$val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';

				
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	private function postcomment(){
		$PostTable = $this->fetchTable('Posts');
		$TableComments = $this->fetchTable('Comments');
		$UserTable = $this->fetchTable('Users');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$postID = $requestData['postId'] ?? null;
		$COMT = $requestData['comment'] ?? null;
		$COMTID = $requestData['commentId'] ?? null;

		if (empty($userId) || empty($postID) || empty($COMT)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId, comment and post_id are required.'
			];
		}
		if($COMTID !=""){
			$comments = $TableComments->find()->where(['Comments.id'=>$COMTID,'Comments.userId'=>$userId])->first();
			if(@$comments->id == ""){
				return [
					'status' => 'Fails',
					'msg' => 'Comment no longer available.'
				];
			}else{
				$comments->comment = $COMT;
			}
		}
		$commentData = [
			'userId' => $userId,
			'postId' => $postID,
			'comment' => $COMT,
			'status' => 1,
		];
		
		
		if($COMTID ==""){
			$comments = $TableComments->newEntity($commentData, ['validate' => false]);
		}
		if ($TableComments->save($comments)) {
			if($COMTID ==""){
				$posts = $PostTable->find()->where(['Posts.id'=>$postID])->first();
				$posts->totalComment += 1;
				$PostTable->save($posts);
			}
			$post_user = $UserTable->find()->where(['Users.id'=>$posts->userId])->first();
			$user_d = $UserTable->find()->where(['Users.id'=>$userId])->first();
			$notification_data = [
				'userId' => $posts->userId,
				'message'=> $user_d->firstName. ' comment your post.',
				'status' => 1,
				'postId' => $posts->id,
				'type_of' => 'Comment',
				'created'=> date('Y-m-d H:i:s')
			];
			$this->notificationsave($notification_data);
			$bodyU=[
				'message'=> $user_d->firstName. ' comment your post.',
				'type' => 'comment'
			];
			if(@$post_user->deviceToken){
				$this->sendNotificationOnAndroid(@$post_user->deviceToken,$bodyU);
			}
			return [
				'status' => 'success',
				'msg' => 'Post Comment successfully.'
			];
		}
	}

	private function commentdelete(){
		$PostTable = $this->fetchTable('Posts');
		$TableComments = $this->fetchTable('Comments');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$COMTID = $requestData['commentId'] ?? null;

		if (empty($userId) || empty($COMTID)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId, and COMTID  are required.'
			];
		}
		
		$comments = $TableComments->find()->where(['Comments.id'=>$COMTID,'Comments.userId'=>$userId])->first();
		if(@$comments->id == ""){
			return [
				'status' => 'Fails',
				'msg' => 'Comment no longer available.'
			];
		}else{
			$posts = $PostTable->find()->where(['Posts.id'=>$comments->postId])->first();
			$posts->totalComment -= 1;
			$PostTable->save($posts);
			$TableComments->delete($comments);
		}
		return [
			'status' => 'success',
			'msg' => 'Comment deleted successfully.'
		];
	}

	function commentlist($postId=NULL, $userId=NULL){
		$TableComments = $this->fetchTable('Comments');
		
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$PostID = ($postId)? $postId : $this->request->getData('postId');
		$status = $this->request->getData('stataus') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($PostID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Comments.id' => 'desc'],
				'page'=>$pageNo,
			];
			//$keyword = $this->request->getData('keyword');
			
			if($status !==""){
				$condition[] = ['Comments.status'=>$status];
			}
			$condition[] = ['Comments.postId'=>$PostID];
			
			$query = $TableComments->find()->where($condition)->contain(['Users','Posts']);
			$totalCount =  $query->count();
			$userservices =array();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['commentId'] 	= $val->id ?? '';
				$Darray[$i]['userId'] 		= $val->userId ?? '';
				$Darray[$i]['postId'] 		= $val->postId ?? '';
				$Darray[$i]['comment'] 		= $val->comment ?? 0;
				$Darray[$i]['stataus'] 		= $val->status ?? 0;
				$Darray[$i]['created'] 		= $val->created ?? '';

				$Darray[$i]['post']['postTitle'] 	= $val->post->postTitle ?? '';
				$Darray[$i]['post']['description'] 	= $val->post->description ?? '';
				$Darray[$i]['post']['image_1'] 		= ($val->post->image_1) ? $this->postPath.$val->post->userId.'/'.$val->post->image_1 : '';
				$Darray[$i]['post']['image_2'] 		= ($val->post->image_2) ? $this->postPath.$val->post->userId.'/'.$val->post->image_2 : '';
				$Darray[$i]['post']['image_3'] 		= ($val->post->image_3) ? $this->postPath.$val->post->userId.'/'.$val->post->image_3 : '';
				$Darray[$i]['post']['image_4'] 		= ($val->post->image_4) ? $this->postPath.$val->post->userId.'/'.$val->post->image_4 : '';
				$Darray[$i]['post']['image_5'] 		= ($val->post->image_5) ? $this->postPath.$val->post->userId.'/'.$val->post->image_5 : '';
				$Darray[$i]['post']['video'] 		= ($val->post->video) ? $this->postPath.$val->post->userId.'/'.$val->post->video : '';
				
				$Darray[$i]['user']['firstName'] 		= $val->user->firstName ?? '';
				$Darray[$i]['user']['email'] 			= $val->user->email ?? '';
				$Darray[$i]['user']['contactNumber'] 	= $val->user->contactNumber ?? '';
				$Darray[$i]['user']['BImage'] 			= (@$val->user->BImage) ? $this->profilePath.$val->user->BImage : '';
				$Darray[$i]['user']['profile_picture'] 	= (@$val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';

				
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	function submitreview(){
		if($this->request->getData['reviewTo'] !='' && $this->request->getData['reviewFrom'] !='' && $this->request->getData['star'] != '' && $this->request->getData['message'] !=''){
				
			$this->loadModel('Reviews');
			$this->loadModel('Users');
			// $reviews = $this->Reviews->find()->where(['Reviews.bookingId'=>$this->request->getData['bookingId'],'Reviews.reviewTo'=>$this->request->getData['reviewTo'],'Reviews.reviewFrom'=>$this->request->getData['reviewFrom']])->first();
			// if(@$reviews->id !=''){
			// 	$response['status'] = 'Fail'; 
			// 	$response['msg'] = 'Revirew already submited.';
			// 	return $response;
			// }
			$reviews = $this->Reviews->newEmptyEntity();
			$reviews = $this->Reviews->patchEntity($reviews, $this->request->getData, ['validate' => false]);
			$reviews['created'] 	= date('Y-m-d H:i:s');
			$reviews['status'] 	= '1';
			$reviews['added_from'] = 'WED';
			$this->Reviews->save($reviews);
			
			//$avg = $this->Reviews->find('all')->where(['Reviews.reviewTo'=>$this->request->getData['reviewTo']])->select(['averagerating' => $query->func()->avg('star')]);
			$query = $this->Reviews->find()->where(['Reviews.reviewTo'=>$this->request->getData['reviewTo']]);
			$avg = $query->select(['averagerating' => $query->func()->avg('star')])->toArray();
			//echo "<pre>"; print_r($avg);
			//echo $avg[0]->averagerating;
			//die;
			
			$ABG['AVGRating'] = round($avg[0]->averagerating,2);
			$users = $this->Users->find()->where(['Users.id'=>$this->request->getData['reviewTo']])->first();
			$users = $this->Users->patchEntity($users, $ABG, ['validate' => false]);
			$this->Users->save($users);
							
			$response['status'] = 'Success'; 
			$response['msg'] = "Review submited successfully.";
		}else{
			$response['status'] = 'Fail'; 
			$response['msg'] = 'reviewTo, reviewFrom, star and message are required.';
		}
		return $response;
	}

	private function reviewlist($reviewfrom=NULL, $reviewto = NULL){
		$this->loadModel('Likes');
		$this->loadModel('User');
		$this->loadModel('Reviews');
		$this->loadModel('Userservices');
		@$USERID = ($reviewfrom) ? $reviewfrom : $this->request->getData['reviewfrom'];
		@$Reviewto = ($reviewto) ? $reviewto : $this->request->getData['reviewto'];
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($Reviewto !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Reviews.id' => 'desc'],
				'page'=>$pageNo,
				'contain' => ['Reviewtos','Reviewfroms']
			];
			$keyword = $this->request->query('keyword');
			$condition[] = ['Reviews.status'=>1];
			if(@$USERID !=''){
				$condition[] = ['Reviews.reviewFrom'=>$USERID];
			}
			if(@$Reviewto !=''){
				$condition[] = ['Reviews.reviewTo'=>$Reviewto];
			}
			$userservices = array();    
			$query = $this->Reviews->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//echo "<pre>"; print_r($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['reviewId'] 					= ($val->id) ? $val->id : '';
				$Darray[$i]['message'] 						= ($val->message) ? $val->message : '';
				$Darray[$i]['star'] 						= ($val->star) ? $val->star : '';
				$Darray[$i]['created'] 						= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
						
				$Darray[$i]['current_time_zone'] 			= (@$val->current_time_zone) ? @$val->current_time_zone : '';
				//$Darray[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
											
				$Darray[$i]['From_userId'] 					= (@$val->reviewfrom->id) ? @$val->reviewfrom->id : '';
				$Darray[$i]['From_firstName'] 				= (@$val->reviewfrom->firstName) ? @$val->reviewfrom->firstName : '';
				$Darray[$i]['From_lastName'] 				= (@$val->reviewfrom->lastName) ? @$val->reviewfrom->lastName : '';
				$Darray[$i]['From_userAddress'] 			= (@$val->reviewfrom->address) ? @$val->reviewfrom->address : '';
				$Darray[$i]['From_profile_picture']			= (@$val->reviewfrom->profile_picture) ? $this->profilePath.@$val->reviewfrom->profile_picture : '';
						
				$Darray[$i]['To_userId'] 					= (@$val->reviewto->id) ? @$val->reviewto->id : '';
				$Darray[$i]['To_firstName'] 				= (@$val->reviewto->firstName) ? @$val->reviewto->firstName : '';
				$Darray[$i]['To_lastName'] 					= (@$val->reviewto->lastName) ? @$val->reviewto->lastName : '';
				$Darray[$i]['To_userAddress'] 				= (@$val->reviewto->address) ? @$val->reviewto->address : '';
				$Darray[$i]['To_profile_picture']			= (@$val->reviewto->profile_picture) ? $this->profilePath.@$val->reviewto->profile_picture : '';
				//$Darray[$i]['timezone'] = $this->timezone();
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
				
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'USERID is required.';
		}
		return  $response;
	}
			

	public function multiimageadd(){
		$TableImages = $this->fetchTable('Images');
		if ($this->request->getData('userId') != '') {
			if (@$_FILES['multiImage']['error']) {
				$uploadDir = WWW_ROOT.'/img/uploads/multiimage/' . $this->request->getData('userId') . '/';
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true); // recursive
				}
				foreach (@$_FILES['multiImage']['error'] as $key => $val) {
					if ($val == '0') {
						$commentData = [
							'userId' => $this->request->getData('userId'),
							'ImageType' => $this->request->getData('ImageType') ?? 1,
							'created' => date('Y-m-d H:i:s'),
						];

						$fileName1 = '';
						if (@$_FILES['multiImage']['name'][$key] != '') {
							$fileName1 = time() . stripslashes($_FILES['multiImage']['name'][$key]);
							$fileName1 = str_replace(" ", "", $fileName1);
							$commentData['image'] = $fileName1;
						}
						$galleries = $TableImages->newEntity($commentData, ['validate' => false]);
						
						$TableImages->save($galleries);
						
						if (@$_FILES['multiImage']['name'][$key] != '' && $fileName1 != '') {
							$target = $uploadDir.$fileName1;
							$source = $_FILES['multiImage']['tmp_name'][$key];
							move_uploaded_file($source,$target);
						}
					}
				}
				$response['status'] = 'success';
				$response['msg'] = 'Image uploaded successfully.';
			} else {
				$response['status'] = 'Fails';
				$response['msg'] = 'Send multi image file.';
			}
		} else {
			$response['status'] = 'Fails';
			$response['msg'] = 'userId, are required.';
		}
		return  $response;
	}

	private function multiimagelist(){
		$TableImages = $this->fetchTable('Images');
		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$status = $requestData['ImageType'] ?? 1;

		if (empty($userId)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId, multi_image_id and status are required.'
			];
		}
		$conditions[] = ['Images.userId'=>$userId];
		if($status){
			$conditions[] = ['Images.ImageType IN'=>explode(",",$status)];
		}
		$multiimg = $TableImages->find()->where($conditions)->toArray();
		$Darray = array();
		$i=0;
		//pr($userservices); die;
		foreach($multiimg as $val){
			$Darray[$i]['multi_image_id'] 	= $val->id ?? '';
			$Darray[$i]['userId'] 			= $val->userId ?? '';
			$Darray[$i]['ImageType'] 			= $val->ImageType ?? '';
			$Darray[$i]['image'] 			= ($val->image) ? $this->multiImagePath.$val->userId.'/'.$val->image : '';
			$i++;
		}
		$response['status'] = "success";
		$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		return  $response;
	}

	private function multiimagedelete(){
		$TableImages = $this->fetchTable('Images');
		if ($this->request->getData('userId') != '' && $this->request->getData('multi_image_id') != '') {
			$multiimg = $TableImages->find()->where(['Images.id'=>$this->request->getData('multi_image_id'), 'Images.userId'=>$this->request->getData('userId')])->first();
			if(empty($multiimg)){
				return [
							'status' => 'Fails',
							'msg' => 'Image no longer avaialble.'
						];
			}
			$folder1 = WWW_ROOT.'/img/uploads/multiimage/' . $multiimg->userId . '/'.$multiimg->image;
			@unlink($folder1);
			$TableImages->delete($multiimg);
			return [
				'status' => 'success',
				'msg' => 'Image deleted successfully.'
			];
		} else {
			$response['status'] = 'Fails';
			$response['msg'] = 'userId, and multi_image_id are required.';
		}
		return  $response;
	}

	private function multiimagechangestatus(){
		$TableImages = $this->fetchTable('Images');
		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;
		$MID = $requestData['multi_image_id'] ?? null;
		$status = $requestData['ImageType'] ?? null;

		if (empty($userId) || empty($MID) || empty($status)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId, multi_image_id and status are required.'
			];
		}
		$multiimg = $TableImages->find()->where(['Images.id'=>$this->request->getData('multi_image_id'), 'Images.userId'=>$this->request->getData('userId')])->first();
		if(empty($multiimg)){
			return [
						'status' => 'Fails',
						'msg' => 'Image no longer avaialble.'
					];
		}
		$multiimg->ImageType = $status;
		$TableImages->save($multiimg);
		return [
			'status' => 'success',
			'msg' => 'Image status changed successfully.'
		];
		
	}


	private function setting(){
		$SettingTable = $this->fetchTable('Settings');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;

		if (empty($userId)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId are required.'
			];
		}
		$setting = $SettingTable->find()->where(['Settings.userId'=>$userId])->first();
		
		
		if(@$setting->id == ""){
			$setting = $SettingTable->newEntity($this->request->getData(), ['validate' => false]);
		}else{
			$setting = $SettingTable->patchEntity($setting, $this->request->getData(), ['validate' => false]);
		}
		if ($SettingTable->save($setting)) {
			return [
				'status' => 'success',
				'msg' => 'Setting updated successfully.'
			];
		}
	}

	private function settingget(){
		$SettingTable = $this->fetchTable('Settings');

		$requestData = $this->request->getData();
		$userId = $requestData['userId'] ?? null;

		if (empty($userId)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId are required.'
			];
		}
		$setting = $SettingTable->find()->where(['Settings.userId'=>$userId])->first();
		
		return [
			'status' => 'success',
			//'msg' => 'Setting updated successfully.'
			'data' => $setting ?? ""
		];
		
	}


	function logout(){
		$articles = TableRegistry::get('Users');
		$query = $articles->query();
		$query->update()
			->set(['deviceToken'=>'','lastLogin'=>date('Y-m-d H:i:s')])
			->where(['id' => $this->request->getData('userId')])
			->execute();
		$response['status'] = 'success';
		return  $response;
	}
	
	function array_filter_recursive($array) {
		foreach ($array as $key => & $value) {
			if (is_array($value)) {
				$value = $this->array_filter_recursive($value);
			}
			else {
				if ( ! is_null($value)) {
				}
				else {
					$array[$key] = '';
				}
			}
		}
		return $array;
	}

	function changepassword(){
		//$this->loadModel('Users');
		$TableUsers = $this->fetchTable('Users');
		if($this->request->getData('userId')){
			$users = $TableUsers->find()->Where(['Users.id' => $this->request->getData('userId')])->first();
			$passwordHasher = new DefaultPasswordHasher();
			$check = $passwordHasher->check($this->request->getData('oldPassword'), $users->password);
			if($check){
				$users = $TableUsers->patchEntity($users, ['password'=> $this->request->getData('newPassword')],['validate' => false]);
				$TableUsers->save($users);
				$response['status'] = 'success';
				$response['msg'] = 'Password update successfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'Current password does not match.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'UserId is required.';
		}
		return  $response;
	}
	
	function forgotpassword(){
		$TableUsers = $this->fetchTable('Users');
		if($this->request->getData('email')){
			$users = $TableUsers->find()->Where(['Users.email' => $this->request->getData('email')])->first();
			//$OLDPASS = base64_decode($users->Pcode);
			//$check = (new DefaultPasswordHasher)->check($this->request->getData('oldPassword'), $users->password);
			if(!empty($users)){
				$newPasss = rand(1111111,99999999);
				$token = $this->random_password(6);
				//echo $token; die;
				$resetUrl = Configure::read('App.siteurl') . 'users/reset/' . $token;
				
				$users->passwordToken = $token;
				$users->p_otp = $token;
				$TableUsers->save($users);
				$to = $users->email;
				//$to = 'raushan.kumar@evirtualservices.com';
				$subject = 'LGBT-TOGO App-Reset Password '.$to;
				$message = "Dear " . ucfirst($users->firstName) .",<br>";
				$message .= "The confidential OTP to reset your paswword is ".$token;
				
				$this->phpemail($to,$subject,$message);
				//echo mail('rksuri2@gmail.com', 'HI', 'HELLO'); die('+++');
				$response['status'] = 'success';
				$response['email'] = $users->email;
				$response['p_otp'] = $token;
				
				$response['msg'] = 'Password sent to the registered email.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'Email does not exist with LGBT-TOGO App.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'email is required.';
		}
		return  $response;
	}

	function resetpassword(){
		$TableUsers = $this->fetchTable('Users');
		if($this->request->getData('email') !='' && $this->request->getData('otp') !='' && $this->request->getData('newPassword') !=''){
			$users = $TableUsers->find()->Where(['Users.email' => $this->request->getData('email')])->first();
			if($users->p_otp == $this->request->getData('otp')){
				$users = $TableUsers->patchEntity($users, ['p_otp'=>'','password'=> $this->request->getData('newPassword')],['validate' => false]);
				$TableUsers->save($users);
				$response['status'] = 'success';
				$response['msg'] = 'Password reset successfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'Invalid OTP Code Entered.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'email, OTP and password are required.';
		}
		return  $response;
	}
	
	function help(){
		$data['phone'] 	= '180-234-5678';
		$data['eamil'] = 'support@LGBT-TOGO App.com';
		$response['status'] = 'success';
		$response['data'] = $data;
		return  $response;
	}
	
	
	

	function userdelete(){
		$TableUsers = $this->fetchTable('Users');
		$TablePosts = $this->fetchTable('Posts');
		$TableComments = $this->fetchTable('Comments');
		$TableLikes = $this->fetchTable('Likes');
		$TableImages = $this->fetchTable('Images');
		$TableFriends = $this->fetchTable('Friends');
		$TableNotifications = $this->fetchTable('Notifications');
		if($this->request->getData('userId') !=''){
			//if(@$this->request->getData('OTP') !=''){
				$users = $TableUsers->find()->where(['Users.id'=>$this->request->getData('userId')])->first();
				if($users->id !=""){
					$TableUsers->delete($users);
					$TableNotifications->deleteAll(['Notifications.userId'=>$users->id]);
					$TablePosts->deleteAll(['Posts.userId'=>$users->id]);
					$TableComments->deleteAll(['Comments.userId'=>$users->id]);
					$TableLikes->deleteAll(['Likes.userId'=>$users->id]);
					$TableImages->deleteAll(['Images.userId'=>$users->id]);
					$TableNotifications->deleteAll(['Notifications.userId'=>$users->id]);
					$TableFriends->deleteAll( ['OR'=>[
									['Friends.senderId'=>$users->id],
									['Friends.receiverId'=>$users->id]
								]
							]);
					// delete for multiple photo
					$folderToDelete = WWW_ROOT.'/img/uploads/multiimage/' . $this->request->getData('userId');
					$this->deleteFolder($folderToDelete);
					// delete POST IMage
					$folderToDelete2 = WWW_ROOT.'/img/uploads/post/' . $this->request->getData('userId');
					$this->deleteFolder($folderToDelete2);

					@unlink(WWW_ROOT . 'img/uploads/users/' . $users->profile_ID_image);
					@unlink(WWW_ROOT . 'img/uploads/users/' . $users->profile_picture);
					@unlink(WWW_ROOT . 'img/uploads/users/' . $users->BImage);

					$response['status'] = "success";
					$response['msg'] = 'User deleted successfully.';
				}else{
					$response['status'] = 'Fail'; 
					$response['msg'] = 'User no longer available.';
				}
			// }else{
			// 	$users = $TableUsers->find()->where(['Users.id'=>$this->request->getData('userId')])->first();
			// 	$users->status = 0;
			// 	$TableUsers->save($users);
			// 	$response['status'] = "success";
			// 	$response['msg'] = 'User deleted successfully.';
			// }
		}else{
			$response['status'] = 'Fail'; 
			$response['msg'] = 'UserId required.';
		}
		return  $response;
	}

	
	
	function sendnotification($id=NULL){
		if($this->request->getData['Token'] !='' && $this->request->getData['message'] !='' && $this->request->getData['device']){
			$body['message'] = $this->request->getData['message'];
			$body = $this->request->getData;
			if(strtolower($this->request->getData['device']) == 'ios'){
				$dd = $this->sednIosPushNotification($this->request->getData['Token'],$body);
			}else{
				$dd = $this->sendNotificationOnAndroid($this->request->getData['Token'],$body);
			}
			$response['status'] = "success";
			$response['response'] = $dd;
			$response['msg'] = "Notification sent.";
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'deviceToken, message and device are required.';
		}
		return  $response;
	}
	

	function notificationtest(){
		//phpinfo();
		$bodyU['message'] 		= 'Your payment of 2000 for your booking successfully processed.';
		$bodyU['type'] 			= 'Payment';
		$eviceToken = "ftVpww5uQ5unh0cO0E9pub:APA91bEerAwKyTAW00KAfpGEfhf3iTkbtgv154yaVY50IB7ooK6dB-egwnHMNnaO-D2EaNvS4M5Qy99vOgAhVb6G5lleiz6XTirH54Q71_6kJhl_Uqrmyrc";
		if(true){
			$this->sednIosPushNotification(@$eviceToken,$bodyU);
		}else{
			$this->sendNotificationOnAndroid(@$eviceToken,$bodyU);
		}
	}

	function notificationsave($save_data=array()){
		$TableNotification = $this->fetchTable('Notifications');
		$notifi = $TableNotification->newEntity($save_data, ['validate' => false]);
		$notifi->created = date('Y-m-d H:i:s');
		$TableNotification->save($notifi);
		
		return true;
	}

	function notificationlist($postId=NULL, $userId=NULL){
		$TableNotification = $this->fetchTable('Notifications');
		
		$VendorID = ($userId)? $userId : $this->request->getData('userId');
		$status = $this->request->getData('stataus') ?? "";
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Notifications.id' => 'desc'],
				'page'=>$pageNo,
			];
			$condition[] = ['Notifications.userId'=>$VendorID];
			
			$query = $TableNotification->find()->where($condition);
			$totalCount =  $query->count();
			$userservices =array();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['notificationId']= $val->id ?? '';
				$Darray[$i]['userId'] 		= $val->userId ?? '';
				$Darray[$i]['message'] 		= $val->message ?? '';
				$Darray[$i]['stataus'] 		= $val->status ?? 0;
				$Darray[$i]['postId'] 		= $val->postId ?? 0;
				$Darray[$i]['type_of'] 		= $val->type_of ?? '';
				$Darray[$i]['read_status'] 	= $val->read_status ?? 0;
				$Darray[$i]['created'] 		= $val->created ?? '';

				
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId is required.';
		}
		return  $response;
	}

	function notificationsend(){
		//phpinfo();
		$bodyU = $this->request->getData();
		$eviceToken = $this->request->getData('deviceToken');
		if($eviceToken){
			$result = $this->sednIosPushNotification(@$eviceToken,$bodyU);
		}else{
			$result = $this->sendNotificationOnAndroid(@$eviceToken,$bodyU);
		}
		$response['status'] = "success";
		$response['msg'] = 'Message sent.';
		$response['data'] = $result;
		return  $response;
	}

	function notificationupdate(){
		$NotificationsTable = $this->fetchTable('Notifications');

		$requestData = $this->request->getData();
		$notificationId = $requestData['notificationId'] ?? null;

		if (empty($notificationId)) {
			return [
				'status' => 'Fails',
				'msg' => 'notificationId are required.'
			];
		}
		$notifi = $NotificationsTable->find()->where(['Notifications.id'=>$notificationId])->first();
		$notifi->read_status = 1;
		$NotificationsTable->save($notifi);
		return [
			'status' => 'success',
			'msg' => 'Notification updated successfully.'
		];
	}

	function blockfriend(){
		$requestData = $this->request->getData();
		$friendID = $requestData['firendId'] ?? null;
		$userId = $requestData['userId'] ?? null;
		$FriendsTable = $this->fetchTable('Friends');
		if (empty($userId) && empty($friendID)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId and friendID are required.'
			];
		}
		$friends = $FriendsTable->find()->where(['Friends.id'=>$friendID, 'OR'=>[
																				['Friends.senderId'=>$userId],
																				['Friends.receiverId'=>$userId]
																			]
												])->first();
		
		if(@$friends->id !=''){
			if($friends->senderId == $userId){
				$friends->block_by_sender = 1;
			}else{
				$friends->block_by_receiver = 1;
			}
			$FriendsTable->save($friends);
			return [
				'status' => 'success',
				'msg' => 'Friend blocked successfully.'
			];
		}else{
			return [
				'status' => 'Fails',
				'msg' => 'Friends no longer available.'
			];
		}
		
	}

	function unblockfriend(){
		$requestData = $this->request->getData();
		$friendID = $requestData['firendId'] ?? null;
		$userId = $requestData['userId'] ?? null;
		$FriendsTable = $this->fetchTable('Friends');
		if (empty($userId) && empty($friendID)) {
			return [
				'status' => 'Fails',
				'msg' => 'userId and friendID are required.'
			];
		}
		$friends = $FriendsTable->find()->where(['Friends.id'=>$friendID, 'OR'=>[
																				['Friends.senderId'=>$userId],
																				['Friends.receiverId'=>$userId]
																			]
												])->first();
		
		if(@$friends->id !=''){
			if($friends->senderId == $userId){
				$friends->block_by_sender = 0;
			}else{
				$friends->block_by_receiver = 0;
			}
			$FriendsTable->save($friends);
			return [
				'status' => 'success',
				'msg' => 'Friend unblocked successfully.'
			];
		}else{
			return [
				'status' => 'Fails',
				'msg' => 'Friends no longer available.'
			];
		}
		
	}

	function reportpost(){
		return [
			'status' => 'success',
			'msg' => ''
		];
	}


	function receiptverification(){
		// server side 

		$url = 'https://sandbox.itunes.apple.com/verifyReceipt';

		// encode the receipt data received from application

		$purchase_encoded = base64_encode( $purchase_receipt );

		//Create JSON

			$encodedData = json_encode( Array( 
				'receipt-data' => $purchase_encoded 
			) );


		// POST data

			//Open a Connection using POST method, as it is required to use POST method.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
			$encodedResponse = curl_exec($ch);
			curl_close($ch);


		//Decode response data using json_decode method to get an object.

			$response = json_decode( $encodedResponse );


		// check response

		if ($response->{'status'} != 0){

			// Invalid receipt

		}else{

		}

		// valid reciept
	}


	function emailtest(){
		$verificationLink = Configure::read('App.siteurl') . 'users/verifyEmail/' ;
		$subject = "Welcome to LGBT-TOGO App! Please verify your email.";
		$message = "Dear Raushan";
		$message .= "<br>Welcome to LGBT-TOGO App! We're delighted to have you as a new member of our platform.";
		$message .= "<br>Please verify your email by clicking the link below:";
		$message .= "<br><br><a href='" . $verificationLink . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email</a>";
		$message .= "<br><br><strong>Note:</strong> Please open this link in the same browser to ensure proper verification.";

		$this->phpemail('raushan@mailinator.com', $subject, $message);
		return [
			'status'=>'success',
			'msg' => 'Sent SUccessfully'
		];
	}


	function notificationcount($userId = NULL){
		$NotificationsTable = $this->fetchTable('Notifications');
		$requestData = $this->request->getData();
		$userId = $userId ?? $requestData['userId'];
		//$NotificationsTable->find()->where(['Notifications.userId'=>$userId, 'Notifications.read_status'=>0])->count();
		return [
			'status'=>'success',
			'noti_unread_count' => $NotificationsTable->find()->where(['Notifications.userId'=>$userId, 'Notifications.read_status'=>0])->count()
		];
	}


	private function subscriptionlist(){
		$NotificationsTable = $this->fetchTable('Subscriptions');
		$subs_list = $this->fetchTable('Subscriptions')->find()->order(['Subscriptions.id'=>'ASC'])->where(['Subscriptions.status'=>1])->toArray();
			
		$PC = array();
		foreach($subs_list as $key=>$VV){
			$PC['id'] = $VV->id;
			$PC['name'] = $VV->name;
			$PC['description'] = $VV->description;
			$PC['valid_day'] = $VV->valid_day;
			$list[] = $PC;
		}
		$response['status'] = "success";
		$response['data'] = $list;
		return $response;
	}


	function deleteFolder($folderPath) {
		// Check if path is a valid directory
		if (!is_dir($folderPath)) {
			return false;
		}

		// Get all files and directories inside the folder
		$items = scandir($folderPath);

		foreach ($items as $item) {
			if ($item === '.' || $item === '..') {
				continue; // Skip current and parent directory
			}

			$fullPath = $folderPath . DIRECTORY_SEPARATOR . $item;

			if (is_dir($fullPath)) {
				// Recursively delete subfolder
				deleteFolder($fullPath);
			} else {
				// Delete file
				unlink($fullPath);
			}
		}

		// Remove the now-empty folder
		return rmdir($folderPath);
	}

	


		
}