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
    var $profilePath 	= 'https://demo5.evirtualservices.net/pusu/img/uploads/users/';
	var $postPath 		= 'https://demo5.evirtualservices.net/pusu/img/uploads/post/';
	var $multiImagePath = 'https://demo5.evirtualservices.net/pusu/img/uploads/multiimage/';
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
				'msg' => 'Email, firstName and password required'
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

			//$this->phpemail($data['email'], $subject, $message);

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

	


		
}