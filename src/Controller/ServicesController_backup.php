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
$fp = fopen('request.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);

class ServicesController extends AppController
{
    var $profilePath 	= 'https://ritevet.com/img/uploads/users/';
	var $dashboardPath 	= 'https://ritevet.com/img/uploads/dashboard/';
	var $catPath 		= 'https://ritevet.com/img/uploads/contact/';
	var $productPath 	= 'https://ritevet.com/img/uploads/products/';
	var $staffPath 		= 'https://ritevet.com/img/uploads/freestaff/';
	var $multiImagePath = 'https://ritevet.com/img/uploads/multiimage/';
	var $e_cat_imagePath = 'https://ritevet.com/img/uploads/elearning_categories/';
	var $e_artical_imagePath = 'https://ritevet.com/img/uploads/elearning_articles/';
	function index(){
		//echo"<pre>"; print_r($this->request->getData());
		//die('GOOD Direct');
		//header("Content-type:application/json");
		Configure::write('Session.checkAgent', false);
		$response=array();
		//$this->layout='';
		if($this->request->getData('action') !=''){
			//$this->request->getData()=$this->request->getData();
		}else{
			$inputJSON = file_get_contents('php://input');
			if($inputJSON){
				$this->request = $this->request->withParsedBody([json_decode($inputJSON, TRUE)]);
			}
		}
		$action = trim($this->request->getData('action'));
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
		$this->loadModel('Users');
		if($this->request->getData('email') !='' && $this->request->getData('firstName') !='' && $this->request->getData('password') !=''){
			$this->loadModel('Users');
			$usr = $this->Users->find()->where(['Users.email' => $this->request->getData('email')])->first();
			if(!empty($usr)){
				$response['status'] = 'Fails';
				$response['msg'] = 'Email already registred with Ritevet';
			}else{
					$this->request->getData['password'] = str_replace(' ', '', $this->request->getData('password'));
					$this->request->getData['email'] = str_replace(' ', '', $this->request->getData('email'));
					$this->request->getData['Pcode'] = base64_encode($this->request->getData['password']);
					$users = $this->Users->newEntity();
					$users = $this->Users->patchEntity($users, $this->request->getData, ['validate' => false]);
					$verificationToken = $this->random_password(50);
					$users['verification_token'] 	= $verificationToken;
					$users['created'] 	= date('Y-m-d H:i:s');
					$users['username'] 	= $this->request->getData('email');
					$users['status'] 	= '1';
					//$users['role'] 		= 'Member';
					$fileName = '';
					if(@$_FILES['image']['name']){
						$fileName=time().stripslashes($_FILES['image']['name']);
						$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
						$rplc =['','',"","","","","","","","","","","","",""];
						$fileName = str_replace($str,$rplc,$fileName);
						$users['profile_picture']=$fileName;
					}
					$fileName2 = '';
					if(@$_FILES['BImage']['name']){
						$fileName2=time().stripslashes($_FILES['BImage']['name']);
						$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
						$rplc =['','',"","","","","","","","","","","","",""];
						$fileName2 = str_replace($str,$rplc,$fileName2);
						$users['BImage']=$fileName2;
					}
					//$path = WWW_ROOT . 'img/uploads/feature/original/';
					if ($this->Users->save($users)) {
							if(@$_FILES['image']['name'] !='' && $fileName !=''){
									$target = WWW_ROOT.'/img/uploads/users/'.$fileName;
									$source=$_FILES['image']['tmp_name'];
									move_uploaded_file($source,$target);
							}
							if(@$_FILES['BImage']['name'] !='' && $fileName2 !=''){
									$target = WWW_ROOT.'/img/uploads/users/'.$fileName2;
									$source=$_FILES['BImage']['tmp_name'];
									move_uploaded_file($source,$target);
							}
							$response['status'] = 'success';
							$response['msg'] = 'Data save successfully.';
							//@mail('raushan.kumar@evirtualservices.com', 'HELLO', 'YES test mail');
							$to=$this->request->getData('email');
							//$to='raushan.kumar@evirtualservices.com';
							
							$sessionToken = $this->random_password(50);
                            $this->request->session()->write('session_token', $sessionToken);
							//$verificationLink = Router::url(['controller' => 'Users', 'action' => 'verifyEmail', $verificationToken, $sessionToken], true);
							$verificationLink = Configure::read('App.siteurl') . 'users/verifyEmail/' . $verificationToken.'/'.$sessionToken;
							$subject="Welcome to RiteVet! Please verify your email.";
							$message="Dear ".ucfirst($this->request->getData('firstName'));
							$message .= "<br>Welcome to RiteVet! We're delighted to have you as a new member of our platform.";
							$message .= "<br>Please verify your email by clicking the link below:";
							$message .= "<br><br><a href='" . $verificationLink . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email</a>";
							$message .= "<br><br><strong>Note:</strong> Please open this link in the same browser to ensure proper verification.";
							$this->phpemail($to, $subject, $message);
							
							$dd = $this->profile($users->id);
							$response['data'] = $dd['data'];
					}
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'Email, contactNumber,fullName and password required';
		}
		return  $response;
	}
	
	
	function socialLoginAction(){
		$this->loadModel('Users');
		if($this->request->getData('socialId') !='' && $this->request->getData('socialType') !=''){
			$this->loadModel('Users');
			if($this->request->getData['image']){
				$this->request->getData['profile_picture']  = $this->request->getData('image');
			}
			$usr = $this->Users->find()->where(['Users.socialId' => $this->request->getData('socialId'),'Users.socialType' => $this->request->getData('socialType')])->first();
			$users = $this->Users->find()->where(['Users.email' => $this->request->getData('email')])->first();
			if(empty($usr)){
				if(empty($users)){
					$users = $this->Users->newEntity();
				}
				$users = $this->Users->patchEntity($users, $this->request->getData, ['validate' => false]);
				$users['created'] 	= date('Y-m-d H:i:s');
				$users['username'] 	= $this->request->getData('email');
				$users['status'] 	= '1';
				$users['role'] 		= 'Member';
				//$users['profile_picture'] 		= $this->request->getData('image');
				
				//$path = WWW_ROOT . 'img/uploads/feature/original/';
				if ($this->Users->save($users)) {
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
	
	
	private function editprofile(){
		$this->loadModel('Users');
		if($this->request->getData('userId') !=''){
			$this->loadModel('Users');
			$users = $this->Users->find()->where(['Users.id' => $this->request->getData('userId')])->first();
			if(!empty($users)){
				//@unset($this->request->getData('email'));
				$fileName3 = '';
				if(@$_FILES['profile_ID_image']['error'] == 0 && @$_FILES['profile_ID_image']['name'] !=''){
					$fileName3=time().stripslashes($_FILES['profile_ID_image']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName3 = str_replace($str,$rplc,$fileName3);
					$this->request->getData['profile_ID_image']=$fileName3;
					$OLDImage = $users->profileId;
				}else{
					unset($this->request->getData['profile_ID_image']);
				}
//echo "<pre>";print_r($this->request->getData);print_r($users); die;
				$users = $this->Users->patchEntity($users, $this->request->getData, ['validate' => false]);
				$fileName = '';
				if(@$_FILES['image']['name']){
					$fileName=time().stripslashes($_FILES['image']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName = str_replace($str,$rplc,$fileName);
					$users['profile_picture']=$fileName;
					$url = $this->profilePath.$users->profile_picture;
					$url2 = $this->profilePath.'thumb/'.$users->profile_picture;
					@unlink($url);
					@unlink($url2);
				}
				
				$fileName2 = '';
				if(@$_FILES['BImage']['name']){
					$fileName2=time().stripslashes($_FILES['BImage']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName2 = str_replace($str,$rplc,$fileName2);
					$users['BImage']=$fileName2;
					$url2 = $this->profilePath.$users->BImage;
					@unlink($url2);
				}

				

				//$path = WWW_ROOT . 'img/uploads/feature/original/';
				if ($this->Users->save($users)) {
						if(@$_FILES['image']['name'] !='' && $fileName !=''){
							$target = WWW_ROOT.'/img/uploads/users/'.$fileName;
							$source=$_FILES['image']['tmp_name'];
							move_uploaded_file($source,$target);
							$MyImageCom = new ImgComponent();
							$MyImageCom->prepare(WWW_ROOT.'/img/uploads/users/'.$fileName);
							$MyImageCom->resize(150,150);
							$MyImageCom->save(WWW_ROOT."img/uploads/users/thumb/".$fileName);
							@chmod(WWW_ROOT."img/uploads/users/thumb/".$fileName, 0777);
						}
						if(@$_FILES['BImage']['name'] !='' && $fileName2 !=''){
								$target = WWW_ROOT.'/img/uploads/users/'.$fileName2;
								$source=$_FILES['BImage']['tmp_name'];
								move_uploaded_file($source,$target);
						}

						if(@$_FILES['profile_ID_image']['name'] !='' && $fileName3 !=''){
							$target = WWW_ROOT.'/img/uploads/users/'.$fileName3;
							$source=$_FILES['profile_ID_image']['tmp_name'];
							@unlink(WWW_ROOT . 'img/uploads/users/'.$OLDImage);
							move_uploaded_file($source,$target);
						}

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
	
	
	private function petparentregistration(){
		$this->loadModel('Users');
		$this->loadModel('Usersinformations');
		$this->loadModel('Typeofservicesrate');
		$this->loadModel('Multilicenses');
		$this->loadModel('Homeservicesinfo');
		if($this->request->getData['userId'] !='' && $this->request->getData['UTYPE'] !=''){
			$sendEmail = 0;
			$users = $this->Usersinformations->find()->where(['Usersinformations.userId' => $this->request->getData['userId'],'Usersinformations.UTYPE' => $this->request->getData['UTYPE']])->first();
			if(empty($users)){
				$sendEmail = 1;
				$users = $this->Usersinformations->newEntity();
			}
				$fileName1 = '';
				if(@$_FILES['ownPicture']['name']){
					$fileName1=time().stripslashes($_FILES['ownPicture']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName1 = str_replace($str,$rplc,$fileName1);
					$this->request->getData['ownPicture']=$fileName1;
					$url1 = $this->profilePath.$users->ownPicture;
					@unlink($url1);
				}else{
					unset($this->request->getData['ownPicture']);
				}
				
				$fileName2 = '';
				if(@$_FILES['BImage']['name']){
					$fileName2=time().stripslashes($_FILES['BImage']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName2 = str_replace($str,$rplc,$fileName2);
					$this->request->getData['BImage']=$fileName2;
					$url2 = $this->profilePath.$users->BImage;
					@unlink($url2);
				}else{
					unset($this->request->getData['BImage']);
				}
				
				$fileName3 = '';
				if(@$_FILES['estimatePrice']['name']){
					$fileName3=time().stripslashes($_FILES['estimatePrice']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName3 = str_replace($str,$rplc,$fileName3);
					$this->request->getData['estimatePrice']=$fileName3;
					$url3 = $this->profilePath.$users->estimatePrice;
					@unlink($url3);
				}else{
					unset($this->request->getData['estimatePrice']);
				}
				
				$fileName4 = '';
				if(@$_FILES['uploadTranscript']['name']){
					$fileName4=time().stripslashes($_FILES['uploadTranscript']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName4 = str_replace($str,$rplc,$fileName4);
					$this->request->getData['uploadTranscript']=$fileName4;
					$url4 = $this->profilePath.$users->uploadTranscript;
					@unlink($url4);
				}else{
					unset($this->request->getData['uploadTranscript']);
				}
				
				$fileName5 = '';
				if(@$_FILES['uploadLicense']['name']){
					$fileName5=time().stripslashes($_FILES['uploadLicense']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName5 = str_replace($str,$rplc,$fileName5);
					$this->request->getData['uploadLicense']=$fileName5;
					$url5 = $this->profilePath.$users->uploadLicense;
					@unlink($url5);
				}else{
					unset($this->request->getData['uploadLicense']);
				}
				
				$fileName6 = '';
				if(@$_FILES['uploadDocument']['name']){
					$fileName6=time().stripslashes($_FILES['uploadDocument']['name']);
					$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
					$rplc =['','',"","","","","","","","","","","","",""];
					$fileName6 = str_replace($str,$rplc,$fileName6);
					$this->request->getData['uploadDocument']=$fileName6;
					$url6 = $this->profilePath.$users->uploadDocument;
					@unlink($url6);
				}else{
					unset($this->request->getData['uploadDocument']);
				}
				
				//pr($this->request->getData); die;
				$users = $this->Usersinformations->patchEntity($users, $this->request->getData, ['validate' => false]);
				$users['modified'] = date('Y-m-d H:i:s');
				if(@$this->request->getData['UTYPE'] == 2 || @$this->request->getData['UTYPE'] == 3 || $users->UTYPE == 2 || $users->UTYPE == 3){
					$users['verifyAdmin'] = 0;
				}
				if ($this->Usersinformations->save($users)) {

					if(@$this->request->getData['service_rate']){
						$rate_data = json_decode(@$this->request->getData['service_rate'], true);
						if(@$rate_data){
							$this->Typeofservicesrate->deleteAll(['Typeofservicesrate.userId'=>$this->request->getData['userId']]);
							foreach($rate_data as $rate){
								$DATA_Rate['userId'] 			= $this->request->getData['userId'];
								$DATA_Rate['typeofservice_id'] 	= $rate['typeofservice_id'];
								$DATA_Rate['rate'] 				= $rate['rate'];
								$DATA_Rate['final_rate'] 		= round( (($rate['rate']*116)/100), 2);
								$DATA_Rate['service_work_days'] = $rate['service_work_days'];
								$s_rates = $this->Typeofservicesrate->newEntity();
								$s_rates = $this->Typeofservicesrate->patchEntity($s_rates, $DATA_Rate, ['validate' => false]);
								$s_rates['created'] = date('Y-m-d H:i:s');
								$this->Typeofservicesrate->save($s_rates);
							}
							
						}
					}

					if(@$this->request->getData['home_service']){
						$H_S_data = json_decode(@$this->request->getData['home_service'], true);
						if(@$H_S_data){
							$this->Homeservicesinfo->deleteAll(['Homeservicesinfo.userId'=>$this->request->getData['userId']]);
							foreach($H_S_data as $rate){
								$DATA_HS['userId'] 				= $this->request->getData['userId'];
								$DATA_HS['question_id'] 		= $rate['question_id'];
								$DATA_HS['answer'] 				= $rate['answer'];
								$s_rates = $this->Homeservicesinfo->newEntity();
								$s_rates = $this->Homeservicesinfo->patchEntity($s_rates, $DATA_HS, ['validate' => false]);
								$s_rates['created'] = date('Y-m-d H:i:s');
								$s_rates['modified'] = date('Y-m-d H:i:s');
								$this->Homeservicesinfo->save($s_rates);
							}
							
						}
					}
					
					if(@$this->request->getData['Multilicenses']){
						@$this->Multilicenses->deleteAll(['Multilicenses.UTYPE'=>$users->UTYPE,'Multilicenses.userId'=>$this->request->getData['userId'],'Multilicenses.userinformationId'=>$users->id]);
						$arr = json_decode($this->request->getData['Multilicenses']);
						foreach($arr as $val){
								$DATA2['UTYPE'] 			= $users->UTYPE;
								$DATA2['userId'] 			= $this->request->getData['userId'];
								$DATA2['userinformationId']= $users->id;
								$DATA2['stateId'] 			= $val->stateId;
								$DATA2['licenceNo'] 		= $val->licenceNo;
								$multilicences = $this->Multilicenses->newEntity();
								$multilicences = $this->Multilicenses->patchEntity($multilicences, $DATA2, ['validate' => false]);
								$multilicences['created'] = date('Y-m-d H:i:s');
								$multilicences['status'] 	= '1';
								$this->Multilicenses->save($multilicences);
						}
					}

					if($sendEmail ==1){
						$usersDD = $this->Users->find()->where(['Users.id'=>$this->request->getData['userId']])->first();
						$to=$usersDD->email;
						//$to='raushan.kumar@evirtualservices.com';
						$subject="Welcome to RiteVet!";
						$message = "Dear " . ucfirst($usersDD->firstName) ." ". ucfirst($usersDD->lastName).",<br>";
						if($this->request->getData['UTYPE'] == 2){
							$message .= "<br>Thank you for registering as a veterinarian with RiteVet! Our management team will review your submitted information and documents.<br>
						Once they complete the review process, you will receive an email confirming that your registration is complete, and you can start using the RiteVet platforms.<br>
                        Additionally, you may receive a phone call from one of our management staff to verify some of your submitted information.<br>
                        This process typically takes between 2 to 14 days.<br>
                        If you have any questions or concerns, feel free to reach out to us at:<br>
                        Email: ritevet@ritevet.com<br>
                        Phone: 321-682-9800<br>
                        Available Monday - Sunday, from 7:00 pm - 10:00 pm (US Eastern Standard Time)<br>
                        If you call outside of these hours or if we are unavailable, please leave a message, and we will get back to you within 48 hours.<br>
                        We appreciate your interest in joining RiteVet and look forward to having you on board!<br>
                        Best Regards,<br>
                        The RiteVet Team<br>";
						}else if($this->request->getData['UTYPE'] == 1){
							
							$message .= "<br>Thank you for registering as a Pet Parent with RiteVet. you can start using the RiteVet platforms.
										If you have any questions or concerns, feel free to reach out to us at:<br>
										Email: ritevet@ritevet.com<br>
										Phone: 321-682-9800<br>
										Available Monday - Sunday, from 7:00 pm - 10:00 pm (US Eastern Standard Time)<br>
										Thank you,<br>
										RiteVet Team<br>";
							
						}else{
							$message .= "<br>Thank you for registering as a Pet Service Provider with RiteVet. Our management staff will review your submitted information and documents. 
					    Once the review process is complete, you will receive an email confirming that your registration is complete, and you can start using the RiteVet platforms.
                        You may also receive a phone call from one of our management staff to verify some of your submitted information. This process will take 2 to 14 days.
                        If you have any questions or concerns, feel free to reach out to us at:<br>
                        Email: ritevet@ritevet.com<br>
                        Phone: 321-682-9800<br>
                        Available Monday - Sunday, from 7:00 pm - 10:00 pm (US Eastern Standard Time)<br>
                        Thank you,<br>
                        RiteVet Team<br>";
						}
						
						$this->phpemail($to, $subject, $message);
					}
					
					if(@$_FILES['ownPicture']['name'] !='' && $fileName1 !=''){
						$target = WWW_ROOT.'/img/uploads/users/'.$fileName1;
						$source=$_FILES['ownPicture']['tmp_name'];
						move_uploaded_file($source,$target);
					}
					if(@$_FILES['BImage']['name'] !='' && $fileName2 !=''){
						$target = WWW_ROOT.'/img/uploads/users/'.$fileName2;
						$source=$_FILES['BImage']['tmp_name'];
						move_uploaded_file($source,$target);
					}
					if(@$_FILES['estimatePrice']['name'] !='' && $fileName3 !=''){
						$target = WWW_ROOT.'/img/uploads/users/'.$fileName3;
						$source=$_FILES['estimatePrice']['tmp_name'];
						move_uploaded_file($source,$target);
					}
					if(@$_FILES['uploadTranscript']['name'] !='' && $fileName4 !=''){
						$target = WWW_ROOT.'/img/uploads/users/'.$fileName4;
						$source=$_FILES['uploadTranscript']['tmp_name'];
						move_uploaded_file($source,$target);
					}
					if(@$_FILES['uploadLicense']['name'] !='' && $fileName5 !=''){
						$target = WWW_ROOT.'/img/uploads/users/'.$fileName5;
						$source=$_FILES['uploadLicense']['tmp_name'];
						move_uploaded_file($source,$target);
					}
					if(@$_FILES['uploadDocument']['name'] !='' && $fileName6 !=''){
						$target = WWW_ROOT.'/img/uploads/users/'.$fileName6;
						$source=$_FILES['uploadDocument']['tmp_name'];
						move_uploaded_file($source,$target);
					}
					$response['status'] = 'success';
					$response['msg'] = 'Data saved successfully';
					$response['msg2'] = "Thank you for registering as a veterinarian with Ritevet! Our management team will review, your submitted infomation and documents Once they complete the review process, you will receive an email confirming that your registrasion is complete, and you can start using the Riteviet platforms.";
					$res = $this->returnprofile($this->request->getData['userId'],$this->request->getData['UTYPE']);
					$response['data'] = $res['data'];
				}
			
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'UserID is required';
		}
		return  $response;
	}
	
	
	function returnprofile($userId=NULL, $UTYPE=NULL){
		//"1" => "Pet Parent Registration Here",
		//"2" => "Veterinarian Register Here",
		//"3" => "Other Pet Services Provider Register Here",
		//"4" => "Request Service",
		//"5" => "Free Stuff",
		//"6" => "Pet Store",
		$American_board = Configure::read('American');
			$this->loadModel('Typeofpets');
			$this->loadModel('Specializations');
			$this->loadModel('Typeofservices');
			$this->loadModel('Users');
			$this->loadModel('Images');
			$this->loadModel('Usersinformations');
			$this->loadModel('Homeservicesinfo');
			$userID = ($userId) ? $userId : $this->request->getData('userId');
			$UTYPEID = ($UTYPE) ? $UTYPE : $this->request->getData('UTYPE');
			$userD = $this->Usersinformations->find()->where(['Usersinformations.userId'=>$userID,'Usersinformations.UTYPE' => $UTYPEID])->contain(['Images','Users'=>['Countries','States','Cities'],'Multilicenses'=>['States'],'Typeofservicesrate','Homeservicesinfo'])->first();
			//$userD = $this->Users->find()->where(['Users.id' => $userID])->contain(['Countries', 'States'])->first();
			//echo "<pre>"; print_r($userD); die('sdsd');
				$UserDetail['userId'] 				= (@$userD->userId) ? $userD->userId : '';
				$UserDetail['userInfoId'] 			= (@$userD->id) ? $userD->id : '';
				$UserDetail['fullName'] 			= (@$userD->user->fullName) ? @$userD->user->fullName : '';
				$UserDetail['lastName'] 			= (@$userD->user->lastName) ? @$userD->user->lastName : '';
				$UserDetail['email'] 				= (@$userD->user->email) ? @$userD->user->email : '';
				$UserDetail['role'] 				= (@$userD->user->role) ? @$userD->user->role : '';
				$UserDetail['address'] 				= (@$userD->user->address) ? @$userD->user->address : '';
				$UserDetail['dob'] 					= (@$userD->user->dob) ? @$userD->user->dob : '';
				$UserDetail['gender'] 				= (@$userD->user->gender) ? @$userD->user->gender : '';
				$UserDetail['city'] 				= (@$userD->user->city) ? @$userD->user->city : '';
				$UserDetail['state'] 				= (@$userD->user->state) ? @$userD->user->state : '';
				$UserDetail['country'] 				= (@$userD->user->country) ? @$userD->user->country : '';
				$UserDetail['zipCode'] 				= (@$userD->user->zipCode) ? @$userD->user->zipCode : '';
				$UserDetail['contactNumber'] 		= (@$userD->user->contactNumber) ? @$userD->user->contactNumber :'';
				$UserDetail['dog_type'] 			= (@$userD->dog_type) ? @$userD->dog_type :'';
				$UserDetail['image'] 				= (@$userD->user->profile_picture) ? $this->profilePath.@$userD->user->profile_picture : '';
				if(strpos(@$userD->user->profile_picture, "http") !== false){
					$UserDetail['image'] = @$userD->user->profile_picture;
				}
				$UserDetail['BImage'] 				= (@$userD->BImage) ? $this->profilePath.@$userD->BImage : '';
				if($UTYPEID ==1 || $UTYPEID ==3 || $UTYPEID ==2){
					$UserDetail['VFirstName'] 	= (@$userD->VFirstName) ? @$userD->VFirstName : '';
					$UserDetail['VmiddleName'] 	= (@$userD->VmiddleName) ? @$userD->VmiddleName : '';
					$UserDetail['VLastName'] 	= (@$userD->VLastName) ? @$userD->VLastName : '';
					$UserDetail['typeOfPets'] 	= (@$userD->typeOfPets) ? @$userD->typeOfPets : '';
					//	$typeOfPetsArray = array();
					//	if(@$userD->typeOfPets !=''){
					//		$ss = explode(",",@$userD->typeOfPets);
					//		$TypePetD = $this->Typeofpets->find('all')->where(['Typeofpets.id IN'=>$ss])->toArray();
					//			$D=0;
					//			foreach($TypePetD as $val){
					//				$typeOfPetsArray[$D]['id'] 			= @$val->id;
					//				$typeOfPetsArray[$D]['name'] 	= @$val->name;
					//				$D++;
					//			}
					//	}
					//$UserDetail['typeOfPetsList'] = $typeOfPetsArray;
					//
					//
					//	$typeOfServiceList = array();
					//	if(@$userD->TypeOfService !=''){
					//		$ss = explode(",",@$userD->TypeOfService);
					//		$TypeServiceD = $this->Typeofservices->find('all')->where(['Typeofservices.id IN'=>$ss])->toArray();
					//			$D2=0;
					//			foreach($TypeServiceD as $val){
					//				$typeOfServiceList[$D2]['id'] 			= @$val->id;
					//				$typeOfServiceList[$D2]['name'] 	= @$val->name;
					//				$D++;
					//			}
					//	}
					//$UserDetail['typeOfServiceList'] = $typeOfServiceList;
					//
					//$typeOfSpecilizationList = array();
					//	if(@$userD->Specialization !=''){
					//		$ss = explode(",",@$userD->Specialization);
					//		$TypeSpecilazationD = $this->Specializations->find('all')->where(['Specializations.id IN'=>$ss])->toArray();
					//			$D3=0;
					//			foreach($TypeSpecilazationD as $val){
					//				$typeOfSpecilizationList[$D3]['id'] 			= @$val->id;
					//				$typeOfSpecilizationList[$D3]['name'] 	= @$val->name;
					//				$D++;
					//			}
					//	}
					//$UserDetail['$typeOfSpecilizationList'] = $typeOfSpecilizationList;
					
					$UserDetail['verifyAdmin'] 			= (@$userD->verifyAdmin) ? @$userD->verifyAdmin : 0;
					$UserDetail['biography'] 			= (@$userD->biography) ? @$userD->biography : '';
					$UserDetail['typeOfBusiness'] 		= (@$userD->typeOfBusiness) ? @$userD->typeOfBusiness : '';
					$UserDetail['TypeOfService'] 		= (@$userD->TypeOfService) ? @$userD->TypeOfService : '';
					$UserDetail['Specialization'] 		= (@$userD->Specialization) ? @$userD->Specialization : '';
					$UserDetail['otherPet'] 			= (@$userD->otherPet) ? @$userD->otherPet : '';
					$UserDetail['otherService'] 		= (@$userD->otherService) ? @$userD->otherService : '';
					$UserDetail['otherSpecialization'] 	= (@$userD->otherSpecialization) ? @$userD->otherSpecialization : '';
					
					$UserDetail['subscriptionCancel']	= (@$userD->subscriptionCancel) ? @$userD->subscriptionCancel : 0; //2=active 1=cancel
					$UserDetail['SubscriptionFrom']		= (@$userD->SubscriptionFrom) ? @$userD->SubscriptionFrom : '';
					$UserDetail['expiryDate'] 			= (@$userD->expiryDate) ? date('Y-m-d',strtotime(@$userD->expiryDate)) : '';
					$UserDetail['purcheseDate'] 		= (@$userD->purcheseDate) ? date('Y-m-d H:i:s',strtotime(@$userD->purcheseDate)) : '';
					
					$UserDetail['american_board_certified'] 	= (@$userD->american_board_certified) ? @$userD->american_board_certified : 0;
					$UserDetail['american_board_certified_option'] 	= (@$userD->american_board_certified_option) ? @$userD->american_board_certified_option : '';
					$name = array();
					if($UserDetail['american_board_certified_option']){
						foreach(explode(",",$UserDetail['american_board_certified_option']) as $B=>$Vx){
							$name[$B] = $American_board[$Vx];
						}
						
					}
					$UserDetail['american_board_certified_option_name'] 		= (@$name) ? implode(",",$name) : '';
					
					$UserDetail['cardNo'] 				= (@$userD->cardNo) ? @$userD->cardNo : '';
					$UserDetail['expMon'] 				= (@$userD->expMon) ? @$userD->expMon : '';
					$UserDetail['expYear'] 				= (@$userD->expYear) ? @$userD->expYear : '';
					$UserDetail['CVV'] 					= (@$userD->CVV) ? @$userD->CVV : '';
					$UserDetail['VBusinessAddress']     = (@$userD->VBusinessAddress) ? @$userD->VBusinessAddress : '';
					
					$UserDetail['Vcity'] 				= (@$userD->Vcity) ? @$userD->Vcity : '';
					$UserDetail['VAstate'] 				= (@$userD->VAstate) ? @$userD->VAstate : '';
					$UserDetail['VZipcode'] 			= (@$userD->VZipcode) ? @$userD->VZipcode : '';
					$UserDetail['BankName'] 			= (@$userD->BankName) ? @$userD->BankName : '';
					$UserDetail['accountType'] 			= (@$userD->BankName) ? @$userD->accountType : '';
					$UserDetail['AccountNo'] 			= (@$userD->AccountNo) ? @$userD->AccountNo : '';
					$UserDetail['RoutingNo']			= (@$userD->RoutingNo) ? @$userD->RoutingNo : '';
					$UserDetail['accountType']			= (@$userD->accountType) ? @$userD->accountType : '';
					$UserDetail['swiftNumber']			= (@$userD->swiftNumber) ? @$userD->swiftNumber : '';
					$UserDetail['ACName'] 				= (@$userD->ACName) ? @$userD->ACName : '';
					//Extra for other pet service START
					$UserDetail['VLicenseNo']			= (@$userD->VLicenseNo) ? @$userD->VLicenseNo : '';
					$UserDetail['VState']				= (@$userD->VState) ? @$userD->VState : '';
					$UserDetail['VBusinessName']		= (@$userD->VBusinessName) ? @$userD->VBusinessName : '';
					$UserDetail['BusinessLicenseNo']	= (@$userD->BusinessLicenseNo) ? @$userD->BusinessLicenseNo : '';
					$UserDetail['VTaxID']				= (@$userD->VTaxID) ? @$userD->VTaxID : '';
					$UserDetail['VBusinessAddress']		= (@$userD->VBusinessAddress) ? @$userD->VBusinessAddress : '';
					$UserDetail['VBSuite']				= (@$userD->VBSuite) ? @$userD->VBSuite : '';
					$UserDetail['Vcity']				= (@$userD->Vcity) ? @$userD->Vcity : '';
					$UserDetail['stateName'] 			= (@$userD->state->name) ? @$userD->state->name : '';
					$UserDetail['VZipcode']				= (@$userD->VZipcode) ? @$userD->VZipcode : '';
					$UserDetail['VPhone']				= (@$userD->VPhone) ? @$userD->VPhone : '';
					$UserDetail['VEmail']				= (@$userD->VEmail) ? @$userD->VEmail : '';
					$UserDetail['YearInBusiness']		= (@$userD->YearInBusiness) ? @$userD->YearInBusiness : '';
					$UserDetail['typeOfBusiness']		= (@$userD->typeOfBusiness) ? @$userD->typeOfBusiness : '';
					$UserDetail['typeOfPetSetting']		= (@$userD->typeOfPetSetting) ? @$userD->typeOfPetSetting : '';
					$UserDetail['typeOfPetSettingOther']= (@$userD->typeOfPetSettingOther) ? @$userD->typeOfPetSettingOther : '';
					$UserDetail['paypalAccount']		= (@$userD->paypalAccount) ? @$userD->paypalAccount : '';
					$UserDetail['PaypalEmail']			= (@$userD->PaypalEmail) ? @$userD->PaypalEmail : '';
					
					$UserDetail['ownPicture'] 			= (@$userD->ownPicture) ? $this->profilePath.@$userD->ownPicture : '';
					$UserDetail['BImage'] 				= (@$userD->BImage) ? $this->profilePath.@$userD->BImage : '';
					$UserDetail['estimatePrice'] 		= (@$userD->estimatePrice) ? $this->profilePath.@$userD->estimatePrice : '';
					$UserDetail['uploadTranscript'] 	= (@$userD->uploadTranscript) ? $this->profilePath.@$userD->uploadTranscript : '';
					$UserDetail['uploadLicense'] 		= (@$userD->uploadLicense) ? $this->profilePath.@$userD->uploadLicense : '';
					$UserDetail['uploadDocument'] 		= (@$userD->uploadDocument) ? $this->profilePath.@$userD->uploadDocument : '';
					$UserDetail['videoChat'] 			= (@$userD->videoChat) ? @$userD->videoChat : 1;
					$UserDetail['AudioChat'] 			= (@$userD->AudioChat) ? @$userD->AudioChat : 1;
					$UserDetail['MessageChat'] 			= (@$userD->MessageChat) ? @$userD->MessageChat : 1;
					
					$service_rate = array();$jj=0;
					if(@$userD->typeofservicesrate){
						foreach($userD->typeofservicesrate as $valj){
							$service_rate[$jj]['typeofservice_id'] 	= $valj->typeofservice_id;
							$service_rate[$jj]['rate'] 				= $valj->rate;
							$service_rate[$jj]['service_work_days'] = $valj->service_work_days;
							$service_rate[$jj]['userId'] 			= $valj->userId;
							$jj++;
						}
					}
					$UserDetail['typeofservice_rate'] 			= (@$service_rate) ? @$service_rate : array();

					$home_service = array();$jk=0;
					if(@$userD->homeservicesinfo){
						foreach($userD->homeservicesinfo as $valj){
							$home_service[$jk]['home_service_id'] 	= $valj->id;
							$home_service[$jk]['question_id'] 		= $valj->question_id;
							$home_service[$jk]['answer'] 			= $valj->answer;
							$home_service[$jk]['userId'] 			= $valj->userId;
							$jk++;
						}
					}
					$UserDetail['home_service'] 			= (@$home_service) ? @$home_service : array();

					$multipleLicense = array();
					if(@$userD->multilicenses){
						foreach($userD->multilicenses as $JJ=>$iVV){
							$multipleLicense[$JJ]['stateId'] = ($iVV->stateId) ? $iVV->stateId : '';
							$multipleLicense[$JJ]['licenceNo'] = ($iVV->licenceNo)? $iVV->licenceNo : '';
							$multipleLicense[$JJ]['stateName'] = (@$iVV->state->name) ? @$iVV->state->name : '';
						}
					}
					$UserDetail['multipleLicense'] = $multipleLicense;

					$multi_Images = array();
					if(@$userD->images){
						foreach($userD->images as $mi=>$imv){
							$DATA['userId'] 		= ($imv->userId) ? $imv->userId : '';
							$DATA['multiimageId'] 	= ($imv->id)? $imv->id : '';
							$DATA['imageType'] 		= (@$imv->imageType) ? @$imv->imageType : '';
							$DATA['UTYPE'] 			= (@$imv->UTYPE) ? @$imv->UTYPE : '';
							$DATA['image']			= (@$imv->image) ? $this->multiImagePath.@$imv->image : '';
							$multi_Images[$imv->imageType][] = $DATA;
 						}
					}
					$UserDetail['multi_image'] = ($multi_Images) ? $multi_Images : (object)array();

					//Extra for other pet service END
				}
				
				
				$response['status'] = 'success';
				$response['data'] = $UserDetail;
			return  $response;
	}
	
	
	function requestservice($userId=NULL){
			$this->loadModel('Usersinformations');
			$this->loadModel('User');
			$this->loadModel('Typeofservices');
			$this->loadModel('Specializations');
			$this->loadModel('Videochatavailability');
			$this->loadModel('Mobileavailability');
			$this->loadModel('Typeofbusines');
			$this->loadModel('Typeofpets');
			$this->loadModel('Typeofservicesrate');
			$American_board = Configure::read('American');
			@$USERID = ($userId) ? $userId : @$this->request->getData('userId');
			@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;

			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Usersinformations.created' => 'desc'],
				'page'=>$pageNo,
				//'contain' => ['Users'=>['Countries','States']]
			];
			$typeofbusiness = $this->request->getData('typeofbusiness');
			if($typeofbusiness != ''){
			$condition[] = ['FIND_IN_SET(\''. $typeofbusiness .'\',Usersinformations.typeOfBusiness)'];
			}
			$keyword = $this->request->getData('keyword');
			if(@$USERID){
				$condition[] = ['Usersinformations.userId <>'=> $USERID];
			}
			$pet_type = @$this->request->getData('pet_type');

			// if($pet_type != ''){
			// 	$condition[] = ['Usersinformations.typeOfPets IN'=>explode(",",$pet_type)];
			// }
			if($pet_type != ''){
				$pet_typeExp = explode(",",$pet_type);
				foreach($pet_typeExp as $vaaaa){
					$condition[] = ['FIND_IN_SET(\''. $vaaaa .'\',Usersinformations.typeOfPets)'];
				}
			}

			$dog_type = @$this->request->getData('dog_type');
			if($dog_type != ''){
				$dog_typeExp = explode(",",$dog_type);
				foreach($dog_typeExp as $vaa){
					$condition[] = ['Usersinformations.dog_type LIKE'=>'%'.$vaa.'%'];
				}
			}

			$avl_date = @$this->request->getData('avl_date');
			if($avl_date != '' && $typeofbusiness !=''){
				$datee = date('D', strtotime($avl_date));
				$uper = strtoupper($datee);
				if($typeofbusiness == 3){  
					$user_list = $this->Videochatavailability->find('list', ['keyField' => 'id', 'valueField' => 'userId'])->where(['Videochatavailability.'.$uper=>1])->toArray();
				}else{
					$user_list = $this->Mobileavailability->find('list', ['keyField' => 'id', 'valueField' => 'userId'])->where(['Mobileavailability.'.$uper=>1])->toArray();
				}
				if($user_list){
					$condition[] = ['Usersinformations.userId IN' =>$user_list];
				}
			}


			$zipcode = @$this->request->getData('zipcode');
			if($zipcode != ''){
				$condition[] = ['Users.zipCode'=>$zipcode];
			}
			$condition[] = ['Usersinformations.verifyAdmin' =>1,'Usersinformations.UTYPE' => $this->request->getData['UTYPE']];
			if($keyword != ''){
				$condition[] = [
					'OR' => [
						["Usersinformations.VState LIKE"=>$keyword."%'"],
						["Usersinformations.VBusinessName LIKE"=>$keyword."%"],
						["Usersinformations.BusinessLicenseNo LIKE"=>$keyword."%"],
						["Usersinformations.VTaxID LIKE"=>$keyword."%'"],
						["Usersinformations.VBusinessAddress LIKE"=>$keyword."%"],
						["Usersinformations.VBSuite LIKE"=>$keyword."%"],
						["Usersinformations.Vcity LIKE"=>$keyword."%"],
						["Usersinformations.VAstate LIKE"=>$keyword."%"],
						["Usersinformations.VZipcode LIKE"=>$keyword."%"],
						["Usersinformations.VPhone LIKE"=>"%".$keyword."%"],
						["Usersinformations.VEmail LIKE"=>"%".$keyword."%"],
					]				
				];
			}        
	
		$userservices =array();    
		$query = $this->Usersinformations->find('all')->where($condition)->contain(['Users'=>['Homeservicesinfo'=>['Homeservicesquestions'],'Typeofservicesrate','Countries','States','Cities']]);
		$totalCount =  $query->count();
		if($pageNo <= ceil($totalCount/10)){
			$userservices = $this->paginate($query);
			$userservices = $userservices->toArray();
		}
		$Darray = array();
		$i=0;
		//echo "<pre>"; print_r($userservices); die;
		foreach($userservices as $val){
			$Darray[$i]['userInformationId']= ($val->id) ? $val->id : '';
			$Darray[$i]['userId'] 			= ($val->userId) ? $val->userId : '';
			$Darray[$i]['firstName'] 		= ($val->user->firstName) ? $val->user->firstName : '';
			$Darray[$i]['lastName'] 		= ($val->user->lastName) ? $val->user->lastName : '';
			$Darray[$i]['address'] 			= ($val->user->address) ? $val->user->address : '';
			$Darray[$i]['biography'] 		= (@$val->biography) ? @$val->biography : '';
			$Darray[$i]['zipCode'] 			= (@$val->user->zipCode) ? @$val->user->zipCode : '';
			$Darray[$i]['image'] 			= ($val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';
			$Darray[$i]['VFirstName'] 		= ($val->VFirstName) ? $val->VFirstName : '';
			$Darray[$i]['VMiddleName'] 		= (@$val->VMiddleName) ? @$val->VMiddleName : '';
			$Darray[$i]['VLastName'] 		= ($val->VLastName) ? $val->VLastName : '';
			$Darray[$i]['ownPicture'] 		= ($val->ownPicture) ? $this->profilePath.$val->ownPicture : '';
			$Darray[$i]['averagerating'] 	= ($val->averagerating) ? $val->averagerating : '0';
			$Darray[$i]['VBusinessName'] 	= ($val->VBusinessName) ? $val->VBusinessName : '';
			$Darray[$i]['VBusinessAddress'] = ($val->VBusinessAddress) ? $val->VBusinessAddress : '';
			$Darray[$i]['VBSuite'] 			= ($val->VBSuite) ? $val->VBSuite : '';
			$Darray[$i]['countryId'] 		= (@$val->user->countryId) ? @$val->user->countryId : '';
			$Darray[$i]['Country'] 			= (@$val->user->country->name) ? @$val->user->country->name : '';
			$Darray[$i]['stateName'] 		= (@$val->user->state->name) ? @$val->user->state->name : '';
			$Darray[$i]['AVGRating'] 		= (@$val->user->AVGRating) ? @$val->user->AVGRating : 0;
			$Darray[$i]['Vcity'] 			= ($val->Vcity) ? $val->Vcity : '';
			$Darray[$i]['VAstate'] 			= ($val->VAstate) ? $val->VAstate : '';
			$Darray[$i]['VZipcode'] 		= ($val->VZipcode) ? $val->VZipcode : '';
			$Darray[$i]['VZipcode'] 		= ($val->VZipcode) ? $val->VZipcode : '';
			$Darray[$i]['VPhone'] 			= ($val->VPhone) ? $val->VPhone : '';
			$Darray[$i]['VEmail'] 			= ($val->VEmail) ? $val->VEmail : '';
			$Darray[$i]['UTYPE'] 			= ($val->UTYPE) ? $val->UTYPE : '';
			$Darray[$i]['YearInBusiness'] 	= ($val->YearInBusiness) ? $val->YearInBusiness : '';
			$Darray[$i]['dog_type'] 		= (@$val->dog_type) ? @$val->dog_type : '';
			$Darray[$i]['Typeofservicesrate'] 		= (@$val->user->typeofservicesrate) ? @$val->user->typeofservicesrate : '';
			$Darray[$i]['Homeservicesinfo'] 		= (@$val->user->homeservicesinfo) ? @$val->user->homeservicesinfo : '';
			$Darray[$i]['typeOfBusiness'] 	= ($val->typeOfBusiness) ? $val->typeOfBusiness : '';
			$Darray[$i]['city'] 			= (@$val->user->city) ? @$val->user->city : array();
			$Darray[$i]['american_board_certified_option'] = ($val->american_board_certified_option) ? $val->american_board_certified_option : '';
			$typeOfSpecilizationList = array();
			if(@$val->Specialization !=''){
				$ss2 = explode(",",@$val->Specialization);
				$TypeSpecilazationD = $this->Specializations->find('all')->where(['Specializations.id IN'=>$ss2])->toArray();
				$D3=0;
				foreach($TypeSpecilazationD as $val3){
					$typeOfSpecilizationList[$D3]['id'] 	= @$val3->id;
					$typeOfSpecilizationList[$D3]['name'] 	= @$val3->name;
					$D3++;
				}
			}
			$Darray[$i]['typeOfSpecilizationList'] = $typeOfSpecilizationList;
			$name = array();
			if($Darray[$i]['american_board_certified_option']){
				foreach(explode(",",$Darray[$i]['american_board_certified_option']) as $B=>$Vx){
					$name[$B] = $American_board[$Vx];
				}
				
			}
			$Darray[$i]['american_board_certified_option_name'] 		= (@$name) ? implode(",",$name) : '';
			//$typeOfServiceList = array();
			//if(@$val->TypeOfService !=''){
			//	$ss = explode(",",@$val->TypeOfService);
			//	$TypeServiceD = $this->Typeofservices->find('all')->where(['Typeofservices.id IN'=>$ss])->toArray();
			//		$D2=0;
			//		foreach($TypeServiceD as $val){
			//			$typeOfServiceList[$D2]['id'] 			= @$val->id;
			//			$typeOfServiceList[$D2]['name'] 	= @$val->name;
			//			$D2++;
			//		}
			//}
			//$Darray[$i]['typeOfServiceList'] = $typeOfServiceList;
			
			//$Darray[$i]['image'] 										= ($val->image) ? $this->productpath.$val->image : '';
			//$Darray[$i]['modified'] 							= ($val->listing->EXP) ? $val->listing->EXP : '';

			$VIDEOCHAT = $this->Videochatavailability->find()->where(['Videochatavailability.userId'=>$val->userId])->first();
			$Darray[$i]['video_price'] = (@$VIDEOCHAT->total_price) ? @$VIDEOCHAT->total_price : 0;
			$Darray[$i]['video_Start_time'] = (@$VIDEOCHAT->startTime) ? @$VIDEOCHAT->startTime : '';
			$Darray[$i]['video_end_time'] = (@$VIDEOCHAT->endTime) ? @$VIDEOCHAT->endTime : '';
			$Darray[$i]['video_time_slot_duration'] = (@$VIDEOCHAT->time_slot_duration) ? @$VIDEOCHAT->time_slot_duration : '';
			$movileCall = $this->Mobileavailability->find()->where(['Mobileavailability.userId'=>$val->userId])->first();
			$Darray[$i]['mobile_price'] = (@$movileCall->total_price) ? @$movileCall->total_price : 0;

			$Darray[$i]['video_availability'] = $this->Videochatavailability->find()->where(['Videochatavailability.userId'=>$val->userId,'Videochatavailability.UTYPE'=>$this->request->getData['UTYPE']])->toArray();
			$Darray[$i]['mobile_availability'] = $this->Mobileavailability->find()->where(['Mobileavailability.userId'=>$val->userId])->toArray();
			

			$Darray[$i]['type_of_pet'] = $this->Typeofpets->find()->where(['Typeofpets.id IN'=>explode(",",$val->typeOfPets)])->toArray();
			$Darray[$i]['type_of_business'] = $this->Typeofbusines->find()->where(['Typeofbusines.id IN'=>explode(",",$val->typeOfBusiness)])->toArray();
			$Darray[$i]['type_of_services'] = $this->Typeofservices->find()->where(['Typeofservices.id IN'=>explode(",",$val->TypeOfService)])->toArray();

			$i++;
		}
		$response['status'] = "success";
		$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		//$response['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.userId'=>$userD->id])->count();
		return  $response;
	}
	
	function requestservicedetails(){
		$this->loadModel('Usersinformations');
		$this->loadModel('User');
		$this->loadModel('Typeofservices');
		$this->loadModel('Specializations');
		$this->loadModel('Videochatavailability');
		$this->loadModel('Mobileavailability');
		$this->loadModel('Typeofbusines');
		$this->loadModel('Typeofpets');
		$this->loadModel('Typeofservicesrate');
		$American_board = Configure::read('American');
		@$USERID = ($userId) ? $userId : @$this->request->getData['service_provider_id'];
		
		if(@$USERID){
			$condition[] = ['Usersinformations.userId'=> $USERID];
		}
		$condition[] = ['Usersinformations.UTYPE' => $this->request->getData['UTYPE']];
		
		$userservices =array();    
		$val = $this->Usersinformations->find()->where($condition)->contain(['Users'=>['Countries','States']])->first();
		$Darray = array();
		$i=0;
		//echo "<pre>"; print_r($userservices); die;
		
			$Darray['userInformationId']= ($val->id) ? $val->id : '';
			$Darray['userId'] 			= ($val->userId) ? $val->userId : '';
			$Darray['firstName'] 		= ($val->user->firstName) ? $val->user->firstName : '';
			$Darray['lastName'] 		= ($val->user->lastName) ? $val->user->lastName : '';
			$Darray['address'] 			= ($val->user->address) ? $val->user->address : '';
			$Darray['biography'] 		= (@$val->biography) ? @$val->biography : '';
			$Darray['zipCode'] 			= (@$val->user->zipCode) ? @$val->user->zipCode : '';
			$Darray['image'] 			= ($val->user->profile_picture) ? $this->profilePath.$val->user->profile_picture : '';
			$Darray['VFirstName'] 		= ($val->VFirstName) ? $val->VFirstName : '';
			$Darray['VMiddleName'] 		= (@$val->VMiddleName) ? @$val->VMiddleName : '';
			$Darray['VLastName'] 		= ($val->VLastName) ? $val->VLastName : '';
			$Darray['ownPicture'] 		= ($val->ownPicture) ? $this->profilePath.$val->ownPicture : '';
			$Darray['averagerating'] 	= ($val->averagerating) ? $val->averagerating : '0';
			$Darray['VBusinessName'] 	= ($val->VBusinessName) ? $val->VBusinessName : '';
			$Darray['VBusinessAddress'] = ($val->VBusinessAddress) ? $val->VBusinessAddress : '';
			$Darray['VBSuite'] 			= ($val->VBSuite) ? $val->VBSuite : '';
			$Darray['countryId'] 		= (@$val->user->countryId) ? @$val->user->countryId : '';
			$Darray['Country'] 			= (@$val->user->country->name) ? @$val->user->country->name : '';
			$Darray['stateName'] 		= (@$val->user->state->name) ? @$val->user->state->name : '';
			$Darray['AVGRating'] 		= (@$val->user->AVGRating) ? @$val->user->AVGRating : 0;
			$Darray['Vcity'] 			= ($val->Vcity) ? $val->Vcity : '';
			$Darray['VAstate'] 			= ($val->VAstate) ? $val->VAstate : '';
			$Darray['VZipcode'] 		= ($val->VZipcode) ? $val->VZipcode : '';
			$Darray['VZipcode'] 		= ($val->VZipcode) ? $val->VZipcode : '';
			$Darray['VPhone'] 			= ($val->VPhone) ? $val->VPhone : '';
			$Darray['VEmail'] 			= ($val->VEmail) ? $val->VEmail : '';
			$Darray['UTYPE'] 			= ($val->UTYPE) ? $val->UTYPE : '';
			$Darray['typeOfBusiness'] 	= ($val->typeOfBusiness) ? $val->typeOfBusiness : '';
			$Darray['dog_type'] 	= ($val->dog_type) ? $val->dog_type : '';
			$Darray['american_board_certified_option'] = ($val->american_board_certified_option) ? $val->american_board_certified_option : '';
			$typeOfSpecilizationList = array();
			if(@$val->Specialization !=''){
				$ss2 = explode(",",@$val->Specialization);
				$TypeSpecilazationD = $this->Specializations->find('all')->where(['Specializations.id IN'=>$ss2])->toArray();
				$D3=0;
				foreach($TypeSpecilazationD as $val3){
					$typeOfSpecilizationList[$D3]['id'] 	= @$val3->id;
					$typeOfSpecilizationList[$D3]['name'] 	= @$val3->name;
					$D3++;
				}
			}
			$Darray[$i]['typeOfSpecilizationList'] = $typeOfSpecilizationList;
			$name = array();
			if($Darray['american_board_certified_option']){
				foreach(explode(",",$Darray['american_board_certified_option']) as $B=>$Vx){
					$name[$B] = $American_board[$Vx];
				}
				
			}
			$Darray['american_board_certified_option_name'] 		= (@$name) ? implode(",",$name) : '';
			
			$VIDEOCHAT = $this->Videochatavailability->find()->where(['Videochatavailability.userId'=>$val->userId])->first();
			$Darray['video_price'] = (@$VIDEOCHAT->total_price) ? @$VIDEOCHAT->total_price : 0;
			$Darray['video_base_price'] = (@$VIDEOCHAT->price) ? @$VIDEOCHAT->price : 0;
			$Darray['video_Start_time'] = (@$VIDEOCHAT->startTime) ? @$VIDEOCHAT->startTime : '';
			$Darray['video_end_time'] = (@$VIDEOCHAT->endTime) ? @$VIDEOCHAT->endTime : '';
			$Darray['video_time_slot_duration'] = (@$VIDEOCHAT->time_slot_duration) ? @$VIDEOCHAT->time_slot_duration : '';
			$movileCall = $this->Mobileavailability->find()->where(['Mobileavailability.userId'=>$val->userId])->first();
			$Darray['mobile_price'] = (@$movileCall->total_price) ? @$movileCall->total_price : 0;
			$Darray['mobile_base_price'] = (@$movileCall->mobileprice) ? @$movileCall->mobileprice : 0;
			

			$Darray['type_of_pet'] = $this->Typeofpets->find()->where(['Typeofpets.id IN'=>explode(",",$val->typeOfPets)])->toArray();
			$Darray['type_of_business'] = $this->Typeofbusines->find()->where(['Typeofbusines.id IN'=>explode(",",$val->typeOfBusiness)])->toArray();
			$Darray['type_of_services'] = $this->Typeofservices->find()->where(['Typeofservices.id IN'=>explode(",",$val->TypeOfService)])->toArray();
			$Darray['type_of_services_rate'] = $this->Typeofservicesrate->find()->where(['Typeofservicesrate.userId'=>$USERID])->toArray();
			
		$response['status'] = "success";
		$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		//$response['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.userId'=>$userD->id])->count();
		return  $response;
	}
	
	function availaibilty (){
		$this->loadModel('Videochatavailability');
		if($this->request->getData['vendorId'] !='' && $this->request->getData['get_date'] !=''){
			$datee = date('D', strtotime($this->request->getData['get_date']));
			$uper = strtoupper($datee);
			$videoavailabe = $this->Videochatavailability->find()->where(['Videochatavailability.userId'=>$this->request->getData['vendorId'],'Videochatavailability.'.$uper=>1])->first();
			if(@$videoavailabe->id !=''){
				$slot = array();
				$i = 0;
				$totalSlote = explode(":", @$videoavailabe->endTime)[0] - explode(":", @$videoavailabe->startTime)[0];
				$j=0;$k=$videoavailabe->time_slot_duration;
				for($i=0; $i<= $totalSlote*2; $i++ ){
					$STAT = date('h:i A',strtotime($videoavailabe->startTime. " + ".$j."minutes "));
					$END = date('h:i A',strtotime($videoavailabe->startTime. " + ".$k."minutes "));
					$slot[$i]['slot'] 		= $STAT."-".$END;
					$j =$j+$videoavailabe->time_slot_duration;
					$k = $k+$videoavailabe->time_slot_duration;
				}
				$response['status'] = "success";
				$response['message'] = 'Find the slot';
				$response['data'] = $slot;
			}else{
				$response['status'] = "success";
				$response['message'] = 'No slot is available';
			}
			
		}else{
			$response['status'] = "Fail";
			$response['message'] = 'vendorId and get_date are required';
		}
		return  $response;
	}



	function bookingrequest(){
		$this->loadModel('Requests');$this->loadModel('Users');
		if($this->request->getData['serviceProvider'] !='' && $this->request->getData['sender'] !='' && $this->request->getData['service_id'] !=''){
			//pr($this->request->getData); die;
			$check_D = $this->Requests->find()->where(['Requests.sender'=>$this->request->getData['sender'],'Requests.time_slot'=>$this->request->getData['time_slot'],'Requests.requested_service_date'=>date('Y-m-d',strtotime($this->request->getData['requested_service_date']))])->first();
			if(@$check_D->id == '' || $this->request->getData['frequency'] == 'Multi-Day'){
				$Booking = $this->Requests->newEntity();
				$Booking = $this->Requests->patchEntity($Booking, $this->request->getData, ['validate' => false]);
				if($this->request->getData['frequency'] == 'Multi-Day'){
					$Booking['requested_service_date'] = ''; 	
				}else{
					$Booking['requested_service_date'] = date('Y-m-d',strtotime($this->request->getData['requested_service_date']));
				}
				$Booking['created'] 	= date('Y-m-d H:i:s');
				$Booking['modified'] 	= date('Y-m-d H:i:s');
				$Booking['sender'] 	= $this->request->getData['sender'];
				$this->Requests->save($Booking);

				$vendor = $this->Users->find()->where(['Users.id'=>$this->request->getData['serviceProvider']])->first();
				$userS = $this->Users->find()->where(['Users.id'=>$this->request->getData['sender']])->first();
				$to = (@$vendor->email) ? @$vendor->email : 'raushan@mailinator.com'; 
				$subject="Ritevet Booking Request";
				$message = "Dear " . ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).",<br>";
				$message .= '<br>We hope this email finds you well.';
				$message .= "You have received a new service request from purnima for Mobile Clinic / Veterinarian Come To My Home.";
				$message .= "Below are the details of the request:";
				$message .= '<br><strong>Business Name</strong>:'.@$vendor->firstName;
				$message .= '<br><strong>Booking Date</strong>: '.@$this->request->getData['requested_service_date'];
				$message .= '<br>You can view the request and respond to the user by logging into your account on our platform.';
				$message .="Thank you for using our platform, and we look forward to your response.
							<br>Best regards,
							<br>Ritevet Team";
				$this->phpemail($to, $subject, $message);

				// $bodyU['message'] 		= 'Your payment of '.$this->request->getData['totalAmount'].' for your recent ride has been successfully processed.';
				// $bodyU['type'] 			= 'Payment';
				// if(strtolower(@$bookings->user->device) =='ios'){
				// 	$this->sednIosPushNotification(@$bookings->user->deviceToken,$bodyU);
				// }else{
				// 	$this->sendNotificationOnAndroid(@$bookings->user->deviceToken,$bodyU);
				// }

				$response['status'] = "success";
				$response['msg'] = 'Booking request successfully';	
			}else{
				$response['status'] = 'Fail';
				$response['msg'] = 'This time slot is already booked by another user for the selected date.';
			}
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'serviceProvider, sender and service_id are required';	
		}
			
		return 	$response;
	}

	function bookingrequestlist($user_id=NULL, $status=NULL){
		$this->loadModel('Requests');
		$this->loadModel('Usersinformations');
		$userId 		= ($user_id) ? $user_id : $this->request->getData['userId'];
		@$STATUS 	= ($status) ? $status : $this->request->getData('status');
		@$reviewTo 	= (@$this->request->getData['reviewTo']) ? @$this->request->getData['reviewTo'] : '';
		$pageNo 		= ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : '1';
		if($userId){
			if(@$this->request->getData['user_type'] == 'Doctor'){
				$condition[] = ['Requests.serviceProvider' => $userId];
			}else{
				$condition[] = ['Requests.sender' => $userId];
			}
			if(@$STATUS !=''){
				$condition[] = ['Requests.status' => $STATUS];
			}

			if(@$reviewTo !=''){
				$condition[] = ['Requests.serviceProvider' => $reviewTo];
			}
			
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Requests.id' => 'desc','Requests.requested_service_date' => 'desc','Requests.multi_date' => 'desc'],
				'page'=>$pageNo,
				'contain' => ['Senders','Bookingbusiness','Serviceproviders','Bookings']
			];
			$userservices =array();    
			$query = $this->Requests->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;		
			//echo "<pre>";
			//print_r($userservices); die;
			if($userservices){
				foreach($userservices as $kry=>$val){
					$list[$i]['requestId']			= ($val->id) ? $val->id : "";
					$list[$i]['serviceProvider']	= (@$val->serviceProvider) ? $val->serviceProvider : "";
					$list[$i]['sender']				= (@$val->sender) ? @$val->sender : "";
					$list[$i]['service_id']			= (@$val->service_id) ? @$val->service_id : "";
					$list[$i]['time_slot']			= (@$val->time_slot) ? @$val->time_slot : "";
					$list[$i]['frequency']			= (@$val->frequency) ? @$val->frequency : "";
					$list[$i]['requested_service_date']			= (@$val->requested_service_date) ? @$val->requested_service_date : "";
					$list[$i]['multi_date']			= (@$val->multi_date) ? @$val->multi_date : "";
					$list[$i]['sender_address']		= (@$val->sender_address) ? @$val->sender_address : "";
					$list[$i]['sender_zipcode']		= (@$val->sender_zipcode) ? @$val->sender_zipcode : "";
					$list[$i]['prefere_times']		= (@$val->prefere_times) ? @$val->prefere_times : "";
					$list[$i]['comment']			= (@$val->comment) ? @$val->comment : "";
					$list[$i]['UTYPE']				= (@$val->UTYPE) ? @$val->UTYPE : "";
					$list[$i]['status']				= (@$val->status) ? @$val->status : "";
					$list[$i]['type_of_services']	= $val->bookingbusines;
					$list[$i]['service_provider']	= @$val->serviceprovider;
					$list[$i]['booking']			= @$val->booking;
					$list[$i]['profile_base_url']	= $this->profilePath;
					// $list[$i]['userEmail']			= (@$val->user->email) ? @$val->user->email : "";
					// $list[$i]['userImage']			= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : "";
					// $list[$i]['contactNumber']		= (@$val->user->contactNumber) ? @$val->user->contactNumber : "";
					// $list[$i]['userAddress']		= (@$val->user->address) ? @$val->user->address : "";
					// $list[$i]['device']				= (@$val->user->device) ? @$val->user->device : "";
					// $list[$i]['deviceToken']		= (@$val->user->deviceToken) ? @$val->user->deviceToken : "";
					// $list[$i]['firebaseId']			= (@$val->user->firebaseId) ? @$val->user->firebaseId : "";
					

					$list[$i]['created']			= date("M jS, Y, g:i a", strtotime($val->created));

					//$list[$i]['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
					//$list[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
										
					$i++;
				}
				$response['status'] = 'Success';
				$response['historyList'] = array_values($this->array_filter_recursive($list));
			}else{
				$response['status'] = 'Success'; 	
				$response['msg'] = 'No record found.';
				$response['historyList'] = array();
			}
		}else{
			$response['status'] = "Fail";	
			$response['msg'] = "userId required";	
		}
		return $response;
	}

	function requestaccept(){
		$this->loadModel('Requests');$this->loadModel('Users');
		if($this->request->getData['requestId'] !='' && $this->request->getData['status'] !='' ){
			$articles = TableRegistry::get('Requests');
			$query = $articles->query();
			$query->update()
				->set(['status' => $this->request->getData['status']])
				->where(['id' => $this->request->getData['requestId']])
				->execute();
			
				$bookings = $this->Requests->find()->where(['Requests.id'=>$this->request->getData['requestId']])->first();
				$vendor = $this->Users->find()->where(['Users.id'=>$bookings->serviceProvider])->first();
				$userS = $this->Users->find()->where(['Users.id'=>$bookings->sender])->first();
				
			if($this->request->getData['status'] == 1){
				$to = (@$userS->email) ? @$userS->email : 'raushan@mailinator.com'; 
				$subject="Ritevet Request Accepted";
				$message = "Dear " . ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).",<br>";
				$message .= '<br>Your appointment has been accepted.';
				$message .= '<br><strong>Business Name</strong>:'.@$vendor->firstName.'';
				$message .= '<br><strong>Booking Date</strong>: '.@$bookings->requested_service_date;
				$message .= '<br><br><a href="https://ritevet.com/users/login">Click to Pay</a>';
				$this->phpemail($to, $subject, $message);
			}

			if($this->request->getData['status'] == 4){
				$to = (@$vendor->email) ? @$vendor->email : 'raushan@mailinator.com'; 
				$subject="Ritevet Request Completed";
				$message = "Dear " . ucfirst(@$userS->firstName) ." ". ucfirst(@$userS->lastName).",<br>";
				$message .= '<br>Your appointment with '. ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).' is on '.@$bookings->requested_service_date.' has been Completed.';
				$message .= '<br>Thanks,';
				$this->phpemail($to, $subject, $message);
			}

			if($this->request->getData['status'] == 5){
				$to = (@$userS->email) ? @$userS->email : 'raushan@mailinator.com'; 
				$subject="Ritevet Request Completed";
				$message = "Dear " . ucfirst(@$userS->firstName) ." ". ucfirst(@$userS->lastName).",<br>";
				$message .= '<br>your appointment with '.ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).' is on '.@$bookings->requested_service_date.' has been Completed.';
				$message .= '<br><br>Thanks,';
				$this->phpemail($to, $subject, $message);
			}

			if($this->request->getData['status'] == 6){
				$to = (@$vendor->email) ? @$vendor->email : 'raushan@mailinator.com'; 
				$subject="Ritevet Request Noshow";
				$message = "Dear " . ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).",<br>";
				$message .= '<br>your appointment with '.ucfirst(@$userS->firstName) ." ". ucfirst(@$userS->lastName).' is on '.@$bookings->requested_service_date.' you didn\'t show.';
				$message .= '<br><br>We need to khow the reason.';
				$this->phpemail($to, $subject, $message);
			}

			$response['status'] = "success";
			$response['msg'] = 'Request accepted successfull.';	
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'requestId and status are required';	
		}
		return 	$response;
	}
	function requestdenied(){
		$this->loadModel('Requests');$this->loadModel('Users');
		if($this->request->getData['requestId'] !='' && $this->request->getData['status'] !='' && $this->request->getData['cancelled_by'] !=''){
			$articles = TableRegistry::get('Requests');
			$query = $articles->query();
			$query->update()
				->set(['cancelled_by'=>$this->request->getData['cancelled_by'], 'status' => $this->request->getData['status']])
				->where(['id' => $this->request->getData['requestId']])
				->execute();
			
				$bookings = $this->Requests->find()->where(['Requests.id'=>$this->request->getData['requestId']])->first();
				$vendor = $this->Users->find()->where(['Users.id'=>$bookings->serviceProvider])->first();
				$userS = $this->Users->find()->where(['Users.id'=>$bookings->sender])->first();
				
				if($this->request->getData['status'] ==2){
					$to = (@$userS->email) ? @$userS->email : 'raushan@mailinator.com'; 
					$subject="Ritevet Request Reject";
					$message = "Dear " . ucfirst(@$userS->firstName) ." ". ucfirst(@$userS->lastName).",<br>";
					$message .= '<br>Your appointment has been rejected.';
					$message .= '<br><strong>Business Name</strong>:'.@$vendor->firstName.'';
					$message .= '<br><strong>Booking Date</strong>: '.date("F jS, Y",strtotime(@$bookings->requested_service_date));
					$this->phpemail($to, $subject, $message);
				}else{
					$to = (@$vendor->email) ? @$vendor->email : 'raushan@mailinator.com'; 
					$subject="Ritevet Request Cancel";
					$message = "Dear " . ucfirst(@$vendor->firstName) ." ". ucfirst(@$vendor->lastName).",<br>";
					$message .= '<br>Your appointment with '. ucfirst(@$userS->firstName) ." ". ucfirst(@$userS->lastName).' is on '.date("F jS, Y",strtotime(@$bookings->requested_service_date)) .' has been canceled.';
					$this->phpemail($to, $subject, $message);
					$to = (@$userS->email) ? @$userS->email : 'raushan@mailinator.com'; 
					$this->phpemail($to, $subject, $message);
				}
				
				
			$response['status'] = "success";
			$response['msg'] = 'Request canceled successfull.';	
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'requestId, cancelled_by and status are required';	
		}
		return 	$response;
	}


	function stripepaymentupdate(){
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
			
			$Booking = $this->Bookings->newEntity();
			$Booking = $this->Bookings->patchEntity($Booking, $BookingData, ['validate' => false]);
			$Booking['bookingDate'] = date('Y-m-d',strtotime(@$request_detail->requested_service_date));
			$Booking['created'] 	= date('Y-m-d H:i:s');
			$this->Bookings->save($Booking);


			$bookings = $this->Requests->find()->where(['Requests.id'=>$this->request->getData['requestId']])->contain(['Bookingservices'])->first();
			$vendor = $this->Users->find()->where(['Users.id'=>$bookings->serviceProvider])->first();
			$userS = $this->Users->find()->where(['Users.id'=>$bookings->sender])->first();
			$to = (@$vendor->email) ? @$vendor->email : 'raushan@mailinator.com'; 
			$subject="Ritevet Request Paid";
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
	
	function addbooking(){
		$this->loadModel('Bookings');
		$this->loadModel('Bookingservices');
		$this->loadModel('Usersinformations');
		$this->loadModel('Typeofservices');
		$this->loadModel('Users');
		if($this->request->getData('vendorId') !='' && $this->request->getData('userId') !='' && $this->request->getData('typeOfServices') !=''){
			//echo "<pre>"; print_r($this->request->getData); //die;
			$TOTALAMOUNT = 0;$adminFee = 5;
			$Booking = $this->Bookings->newEntity();
			$INVO['vendorId'] 			= $this->request->getData('vendorId');
			$INVO['userId'] 			= $this->request->getData('userId');
			$INVO['typeofbusinessId']	= $this->request->getData('typeofbusinessId');
			$INVO['UTYPE'] 				= $this->request->getData('UTYPE');
			$INVO['typeOfServices'] 	= $this->request->getData('typeOfServices');
			$INVO['current_time_zone']	 = $this->request->getData('current_time_zone');

			$INVO['keyword_1']	 = @$this->request->getData('keyword_1');
			$INVO['keyword_2']	 = @$this->request->getData('keyword_2');
			$INVO['keyword_3']	 = @$this->request->getData('keyword_3');

			//$EXP = json_decode($this->request->getData['servicelist']);
			//foreach($EXP as $VV){
			//	$TOTALAMOUNT +=$VV->price;
			//}
			$INVO['adminFee'] 	= $adminFee;
			$INVO['totalAmount'] 		= ($this->request->getData['amount'] > 5) ? ($this->request->getData['amount']-$adminFee) : 0;
			$EXXP = explode("-", $this->request->getData('slotTime'));
			$INVO['slotTime'] 		= date('H:i', strtotime(trim($EXXP[0])))."-".date('H:i', strtotime(trim($EXXP[1])));
			$INVO['comment'] 		= '';
			$INVO['transactionId'] 	= $this->request->getData('transactionId');
			$INVO['payment_mode'] 	= $this->request->getData('payment_mode');
			$INVO['status'] 		= 1;
			$Booking = $this->Bookings->patchEntity($Booking, $INVO, ['validate' => false]);
			$Booking['bookingDate'] = date('Y-m-d',strtotime($this->request->getData['bookingDate']));
			$Booking['added_time'] = date('Y-m-d H:i:s',strtotime($this->request->getData['added_time']));
			$Booking['created'] 	= date('Y-m-d H:i:s');
			$this->Bookings->save($Booking);
			$INVOIDE_ID=$Booking->id;
			
			//echo "<pre>"; print_r($EXP); die;
			$serviceNAME = array();
			$EXP = explode(",", $this->request->getData['typeOfServices']);
			foreach($EXP as $KEY=>$vall){
				$det = $this->Typeofservices->find()->where(['Typeofservices.id'=>$vall])->first();
				$bookingServices = $this->Bookingservices->newEntity();
				$package['bookingId'] 		= $INVOIDE_ID;
				$package['serviceId'] 		= @$det->id;
				$package['serviceName'] 	= @$det->name;
				$package['price'] 			= 0;
				$package['quantity'] 		= '1';
				$package['description'] 	= '';
				$serviceNAME[$KEY] = @$det->name;
				$package['totalPrice'] 	= ($package['quantity'] * $package['price']);
				$bookingServices = $this->Bookingservices->patchEntity($bookingServices, $package, ['validate' => false]);
				$bookingServices['created'] = date('Y-m-d H:i:s');
				$this->Bookingservices->save($bookingServices);
			}
			$userS = $this->Users->find()->where(['Users.id'=>$this->request->getData['userId']])->first();
			$vendorS = $this->Usersinformations->find()->where(['Usersinformations.userId'=>$this->request->getData['vendorId'], 'Usersinformations.UTYPE'=>$this->request->getData['UTYPE']])->contain(['Users'])->first();
			if(@$vendorS->id !=''){
				//$this->sendNotificationOnAndroid('');
				$to=@$vendorS->VEmail;
				//$to='raushan.kumar@evirtualservices.com';
				$subject="Ritevet Booking Confirmation";
				$message="Dear ".ucfirst(@$vendorS->VBusinessName);
				$message .= '<br>Your appointment with '.$userS->firstName.' is on '.$this->request->getData['bookingDate'].' at '.$this->request->getData['slotTime'].' for the service(s) '.implode(", ", $serviceNAME);
				$message .= '<br><br>We look forward to seeing you then.';
				$message .= '<br><br>Thanks,';
				$this->phpemail($to, $subject, $message);
				$deviceToken = @$vendorS->user->deviceToken;
				$body['message'] = 'Your appointment with '.$userS->firstName.' is on '.$this->request->getData['bookingDate'].' at '.$this->request->getData['slotTime'].' for the service(s) '.implode(", ", $serviceNAME);;
				$body['type'] = 'text';
				if(strtolower(@$vendorS->user->device) == 'ios'){
					$this->sednIosPushNotification($deviceToken,$body);
				}else{
					$this->sendNotificationOnAndroid($deviceToken,$body);
				}
				//$this->sendNotificationOnAndroid($deviceToken,$body);
			}
			if(@$userS->id !=''){
				$to=@$userS->email;
				//$to='raushan.kumar@evirtualservices.com';
				$subject="Ritevet Booking Confirmation";
				$message = "Dear " . ucfirst(@$userS->firstName) ." ". ucfirst(@$userS->lastName).",<br>";
				$message .= '<br>Your appointment has been booked successfully.';
				$message .= '<br><strong>Business Name</strong>: '.@$vendorS->VBusinessName;
				$message .= '<br><strong>Booking Date</strong>: '.$this->request->getData['bookingDate'];
				$message .= '<br><strong>Booking Slot</strong>: '.$this->request->getData['slotTime'];
				$message .= '<br><strong>Service Name</strong>: '.implode(", ", $serviceNAME);
				$this->phpemail($to, $subject, $message);
				$deviceToken = @$userS->deviceToken;
				$body['message'] = 'Your appointment has been booked successfully. Business Name:'.@$vendorS->VBusinessName.', Booking Date:'.$this->request->getData['bookingDate'].', Booking Slot:'.$this->request->getData['slotTime'].", ".implode(", ", $serviceNAME);
				$body['type'] = 'text';
				//$this->sendNotificationOnAndroid($deviceToken,$body);
				if(strtolower(@$userS->device) == 'ios'){
					$this->sednIosPushNotification($deviceToken,$body);
				}else{
					$this->sendNotificationOnAndroid($deviceToken,$body);
				}
			}

			$response['status'] = 'Success';
			$response['msg'] = 'Booking completed succssfully';
			$response['bookingId'] = $INVOIDE_ID;
					//$response['data'] = $RES;
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'vendorId, userId and serviceId are required';	
		}
			
		return 	$response;
	}
	
	/*this is for BOOKING COME*/
	function bookinglist($user_id=NULL, $status=NULL){
		$this->loadModel('Bookingservice');
		$this->loadModel('Bookings');
		$this->loadModel('Usersinformations');$this->loadModel('Typeofbusines');
		
			$userId 		= ($user_id) ? $user_id : $this->request->getData('userId');
			@$STATUS 	= ($status) ? $status : $this->request->getData('status');
			$pageNo 		= ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : '1';
		if($userId){
			$condition[] = ['Bookings.vendorId' => $userId];
			$TypeOfBusinessList = $this->Typeofbusines->find('list', ['keyField' => 'id', 'valueField' => ['name']])->where(['Typeofbusines.status'=>1])->order(['Typeofbusines.name'=>'ASC'])->toArray();
				//if(@$STATUS != ''){
				//		$condition[] = ['Bookings.status' => $STATUS];
				//}
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Bookings.created' => 'desc'],
				'page'=>$pageNo,
				'contain' => ['Bookingservices','Users','Vendors']
			];
			$userservices =array();    
			$query = $this->Bookings->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;		
			//echo "<pre>";
			//print_r($userservices); die;
			if($userservices){
				foreach($userservices as $kry=>$val){
					$list[$i]['bookingID']			= ($val->id) ? $val->id : "";
					$list[$i]['userID']				= (@$val->userId) ? $val->userId : "";
					$list[$i]['vendorID']			= (@$val->vendorId) ? @$val->vendorId : "";
					$list[$i]['userName']			= (@$val->user->fullName) ? @$val->user->fullName : "";
					$list[$i]['userEmail']			= (@$val->user->email) ? @$val->user->email : "";
					$list[$i]['userImage']			= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : "";
					$list[$i]['contactNumber']		= (@$val->user->contactNumber) ? @$val->user->contactNumber : "";
					$list[$i]['userAddress']		= (@$val->user->address) ? @$val->user->address : "";
					$list[$i]['device']				= (@$val->user->device) ? @$val->user->device : "";
					$list[$i]['deviceToken']		= (@$val->user->deviceToken) ? @$val->user->deviceToken : "";
					$list[$i]['firebaseId']			= (@$val->user->firebaseId) ? @$val->user->firebaseId : "";
					
					$list[$i]['totalAmount']		= $val->totalAmount;
					$list[$i]['bookingDate']		= $val->bookingDate;
					$list[$i]['slotTime']			= $val->slotTime;
					$list[$i]['status']				= $val->status;
					$list[$i]['transactionId']		= $val->transactionId;
					$list[$i]['typeofbusinessId']	= $val->typeofbusinessId;
					$list[$i]['typeofbusinessName']	= @$TypeOfBusinessList[@$val->typeofbusinessId];
					$list[$i]['UTYPE']				= $val->UTYPE;

					$list[$i]['keyword_1']						= $val->keyword_1;
					$list[$i]['keyword_2']						= $val->keyword_2;
					$list[$i]['keyword_3']						= $val->keyword_3;

					$list[$i]['created']			= date("M jS, Y, g:i a", strtotime($val->created));

					$list[$i]['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
					$list[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
										
					$serviceArray					=  array();
					if($val->Bookingservices){
						foreach($val->Bookingservices as $key=>$K){
							array_push($serviceArray,$K->name);
						}
					}
					//$list[$i]['serviceArray'] = implode(",",$serviceArray);
					$i++;
				}
				$response['status'] = 'Success';
				$response['historyList'] = array_values($this->array_filter_recursive($list));
			}else{
				$response['status'] = 'Success'; 	
				$response['msg'] = 'No record found.';
				$response['historyList'] = array();
			}
		}else{
			$response['status'] = "Fail";	
			$response['msg'] = "userId required";	
		}
		return $response;
	}
	/*this is for i haved booked the appointment*/
	function bookinglist2($user_id=NULL, $status=NULL){
		$this->loadModel('Bookingservice');
		$this->loadModel('Bookings');
		$this->loadModel('Usersinformations');$this->loadModel('Typeofbusines');
		
			$userId = ($user_id) ? $user_id : $this->request->getData('userId');
			@$STATUS = ($status) ? $status : $this->request->getData('status');
			$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : '1';
		if($userId){
			$condition[] = '';
			$condition[] = ['Bookings.userId' => $userId];
				
			$TypeOfBusinessList = $this->Typeofbusines->find('list', ['keyField' => 'id', 'valueField' => ['name']])->where(['Typeofbusines.status'=>1])->order(['Typeofbusines.name'=>'ASC'])->toArray();
				//if(@$STATUS != ''){
				//		$condition[] = ['Bookings.status' => $STATUS];
				//}
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => [
						'Bookings.created' => 'desc'
					],
				'page'=>$pageNo,
				'contain' => ['Bookingservices','Users','Vendors']
			];
			$userservices =array();    
			$query = $this->Bookings->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;		
			//echo "<pre>";
			//print_r($userservices); die;
			if($userservices){
				foreach($userservices as $kry=>$val){
					$list[$i]['bookingID']					= ($val->id) ? $val->id : "";
					$list[$i]['userID']						= (@$val->userId) ? $val->userId : "";
					$list[$i]['vendorID']					= (@$val->vendorId) ? @$val->vendorId : "";
					$US = $this->Usersinformations->find()->where(['Usersinformations.userId'=>@$val->vendorId,'Usersinformations.UTYPE'=>$val->UTYPE])->first();
					$list[$i]['userName']					= (@$US->VFirstName) ? @$US->VFirstName.' '.@$US->VLastName : "";
					$list[$i]['userEmail']					= (@$US->VEmail) ? @$US->VEmail : "";
					$list[$i]['userImage']					= (@$US->ownPicture) ? $this->profilePath.@$US->ownPicture : "";
					$list[$i]['contactNumber']				= (@$US->VPhone) ? @$US->VPhone : "";
					$list[$i]['userAddress']				= (@$US->VBusinessAddress) ? @$US->VBusinessAddress : "";
					$list[$i]['device']						= (@$val->vendor->device) ? @$val->vendor->device : "";
					$list[$i]['deviceToken']				= (@$val->vendor->deviceToken) ? @$val->vendor->deviceToken : "";
					$list[$i]['firebaseId']					= (@$val->vendor->firebaseId) ? @$val->vendor->firebaseId : "";
					
					$list[$i]['totalAmount']				= $val->totalAmount;
					$list[$i]['bookingDate']				= $val->bookingDate;
					$list[$i]['slotTime']					= $val->slotTime;
					$list[$i]['status']						= $val->status;
					$list[$i]['transactionId']				= $val->transactionId;
					$list[$i]['typeofbusinessId']			= $val->typeofbusinessId;
					$list[$i]['typeofbusinessName']			= @$TypeOfBusinessList[$val->typeofbusinessId];
					$list[$i]['UTYPE']						= $val->UTYPE;
					$list[$i]['created']					= date("M jS, Y, g:i a", strtotime($val->created));

					$list[$i]['keyword_1']						= $val->keyword_1;
					$list[$i]['keyword_2']						= $val->keyword_2;
					$list[$i]['keyword_3']						= $val->keyword_3;

					$list[$i]['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
					$list[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
						

					$serviceArray				=  array();
					if($val->Bookingservices){
						foreach($val->Bookingservices as $key=>$K){
							array_push($serviceArray,$K->name);
						}
					}
					//$list[$i]['serviceArray'] = implode(",",$serviceArray);
					$i++;
				}
				$response['status'] = 'Success';
				
				$response['historyList'] = array_values($this->array_filter_recursive($list));
			}else{
					$response['status'] = 'Success'; 	
					$response['msg'] = 'No record found.';
					$response['historyList'] = array();
			}
		}else{
			$response['status'] = "Fail";	
			$response['msg'] = "userId required";	
		}
		return $response;
	}
	
	
	function bookingdetail($bookingId=NULL, $user_id=NULL){
		$this->loadModel('Bookingservice');
		$this->loadModel('Bookings');$this->loadModel('Reviews');
		$this->loadModel('Usersinformations');$this->loadModel('Typeofbusines');
		$TypeOfBusinessList = $this->Typeofbusines->find('list', ['keyField' => 'id', 'valueField' => ['name']])->where(['Typeofbusines.status'=>1])->order(['Typeofbusines.name'=>'ASC'])->toArray();
		$userId = ($user_id) ? $user_id : $this->request->getData('userId');
		$bookingID = ($bookingId) ? $bookingId : $this->request->getData('bookingId');
		//@$UserType = ($usertype) ? $usertype : $this->request->getData('usertype');
		if($bookingID){
			$condition[] = ['Bookings.id' => $bookingID];
			$val = $this->Bookings->find('all')->where($condition)->contain(['Bookingservices','Users','Vendors'])->first();
			$Darray = array();
			$i=0;		
			//echo "<pre>";
			//print_r($val); die;
			if($val){
				$list['bookingID']			= ($val->id) ? $val->id : "";
				$list['userID']				= (@$val->userId) ? $val->userId : "";
				$list['vendorID']			= (@$val->vendorId) ? @$val->vendorId : "";
				//if($usertype == 'Member'){
				//echo $val->vendor->id; die;
				$list['userNumber']			= (@$val->user->contactNumber) ? @$val->user->contactNumber : "";
				$list['userName']			= (@$val->user->fullName) ? @$val->user->fullName." ".@$val->user->lastName : "";
				$list['userEmail']			= (@$val->user->email) ? @$val->user->email : "";
				$list['userImage']			= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : "";
				$list['userAddress']		= (@$val->user->address) ? @$val->user->address : "";
				$list['userdevice']			= (@$val->user->device) ? @$val->user->device : "";
				$list['userdeviceToken']	= (@$val->user->deviceToken) ? @$val->user->deviceToken : "";
				$list['userfirebaseId']		= (@$val->user->firebaseId) ? @$val->user->firebaseId : "";
				
				
				$US = $this->Usersinformations->find()->where(['Usersinformations.userId'=>@$val->vendorId,'Usersinformations.UTYPE'=>$val->UTYPE])->first();
				//$list[$kry]['userName']		= (@$val->vendor->fullName) ? @$val->vendor->fullName : "";
				//$list[$kry]['userImage']		= (@$val->vendor->profile_picture) ? $this->profilePath.@$val->vendor->profile_picture : "";
				//$list[$kry]['contactNumber']	= (@$val->vendor->contactNumber) ? @$val->vendor->contactNumber : "";
				$list['vendorName']			= (@$US->VFirstName) ? @$US->VFirstName.' '.@$US->VLastName : "";
				$list['vendorEmail']		= (@$US->VEmail) ? @$US->VEmail : "";
				$list['vendorImage']		= (@$US->ownPicture) ? $this->profilePath.@$US->ownPicture : "";
				$list['vendorNumber']		= (@$US->VPhone) ? @$US->VPhone : "";
				$list['vendorAddress']		= (@$US->VBusinessAddress) ? @$US->VBusinessAddress : "";
				$list['vendordevice']		= (@$val->vendor->device) ? @$val->vendor->device : "";
				$list['vendordeviceToken']	= (@$val->vendor->deviceToken) ? @$val->vendor->deviceToken : "";
				$list['vendorfirebaseId']	= (@$val->vendor->firebaseId) ? @$val->vendor->firebaseId : "";
				
				$list['totalAmount']		= $val->totalAmount;
				$list['bookingDate']		= date('Y-m-d',strtotime($val->bookingDate));
				$list['slotTime']			= $val->slotTime;
				$list['status']				= $val->status;
				$list['transactionId']		= $val->transactionId;
				$list['typeofbusinessId']	= $val->typeofbusinessId;
				$list['typeofbusinessName']	= @$TypeOfBusinessList[$val->typeofbusinessId];
				$list['UTYPE']				= $val->UTYPE;

				$list['keyword_1']			= $val->keyword_1;
				$list['keyword_2']			= $val->keyword_2;
				$list['keyword_3']			= $val->keyword_3;

				$list['created']			= date("M jS, Y, g:i a", strtotime($val->created));

				$list['current_time_zone'] 	= (@$val->current_time_zone) ? @$val->current_time_zone : '';
				$list['added_time'] 		= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
						

				$serviceArray				=  array();
				if($val->bookingservices){
					foreach($val->bookingservices as $key=>$K){
						array_push($serviceArray,$K->serviceName);
					}
				}
				
				$list['serviceArray'] = implode(",",$serviceArray);
				$list['serviceArray'] = $serviceArray;
				$i++;
				
				$list['reviewByYou'] = $this->Reviews->find()->where(['Reviews.reviewFrom'=>$val->userId,'Reviews.bookingId'=>$bookingID])->count();
			}
			$response['status'] = 'Success';
			$response['data'] = ($list) ? $list : array();
			
		}else{
			$response['status'] = "Fail";	
			$response['msg'] = "userId required";	
		}
		return $response;
	}
	
	function bookingcomplete(){
		$this->loadModel('Bookings');$this->loadModel('Usersinformations');
		if($this->request->getData['userId'] !='' && $this->request->getData['bookingId'] !='' && $this->request->getData['status'] !=''){
			$bookingD = $this->Bookings->find()->where(['Bookings.id'=>$this->request->getData['bookingId']])->contain(['Vendors'])->first();
			if($bookingD->id !=''){
				$articles = TableRegistry::get('Bookings');
				$query = $articles->query();
				$query->update()
					->set(['status' => $this->request->getData['status']])
					->where(['id' => $bookingD->id])
					->execute();
				if($this->request->getData['status'] == 2 && $bookingD->UTYPE == 2){
					$u_info = $this->Usersinformations->find()->where(['Usersinformations.UTYPE'=>2,'Usersinformations.userId'=>$this->request->getData['userId']])->first();
					$articles2 = TableRegistry::get('Usersinformations');
					$query2 = $articles2->query();
					$query2->update()
						->set(['wallet' => ($u_info->wallet + $bookingD->totalAmount)])
						->where(['id' => $u_info->id])
						->execute();
				}
				$response['status'] = 'Success';
				$response['msg'] = "Appointment status has been changed successfully.";
			}else{
				$response['status'] = "Fail";	
				$response['msg'] = "userId, bookingId and status are required";
			}
		}else{
			$response['status'] = "Fail";	
			$response['msg'] = "userId, bookingId and status are required";	
		}
		return $response;
		
	}
	
	
	
	function bookingdetails($booking_id=NULL){
		$this->loadModel('Bookingservice');
		$this->loadModel('Booking');
		$this->loadModel('User');
		
		$invoiceId = ($booking_id) ? $booking_id : $this->request->getData('bookingId');
		
		
		if($invoiceId){
			
			$val =	$this->Booking->find('first',array('conditions'=>array('Booking.id'=>$invoiceId)));
			//echo "<pre>";
			//print_r($val); die;
			if(@$val){
					
				$list['bookingID']			= ($val['Booking']['id']) ? $val['Booking']['id'] : "";
				$list['userID']				= ($val['User']['id']) ? $val['User']['id'] : "";
				$list['userName']			= ($val['User']['firstName']) ? $val['User']['firstName'] : "";
				$list['userimage']			= ($val['User']['image']) ? $this->profilePath.$val['User']['image'] : "";
				$list['userAddress']		= ($val['User']['address']) ? $val['User']['address'] : "";
				
				$list['vendorID']			= ($val['Vendor']['id']) ? $val['Vendor']['id'] : "";
				$list['vendorName']			= ($val['Vendor']['firstName']) ? $val['Vendor']['firstName'] : "";
				$list['vendorimage']		= ($val['Vendor']['image']) ? $this->profilePath.$val['Vendor']['image'] : "";
				$list['vendorAddress']		= ($val['Vendor']['address']) ? $val['Vendor']['address'] : "";
				
				$list['serviceFee']			= $val['Booking']['serviceFee'];
				$list['tax']				= $val['Booking']['tax'];
				$list['totalAmount']		= $val['Booking']['totalAmount'];
				$list['bookingDate']		= $val['Booking']['bookingDate'];
				$list['slotTime']			= $val['Booking']['slotTime'];
				$list['status']				= $val['Booking']['status'];
				$list['transactionId']		= $val['Booking']['transactionId'];
				$list['created']			= $val['Booking']['created'];
					
				$list['servicelist']				=  array();
				foreach($val['Bookingservice'] as $key=>$K){
						$list['servicelist'][$key]['name']			= $K['serviceName'];
						$list['servicelist'][$key]['price'] 		= $K['price'];
						$list['servicelist'][$key]['quantity'] 		= ($K['quantity']) ? $K['quantity'] : '0';
						$list['servicelist'][$key]['id'] 			= $K['id'];
						$list['servicelist'][$key]['description'] 	= $K['description'];
						$list['servicelist'][$key]['subserviceId'] 	= $K['serviceId'];
				}
				
				$response['status'] = 'Success';
				$response['historyList'] = $this->array_filter_recursive($list);
			}else{
					$response['status'] = 'Fail'; 	
					$response['msg'] = "booking no longer available.";	
			}
		}else{
			$response['status'] = "Fail";	
			$response['msg'] = "invoiceId required";	
		}
		return $response;
	}


	function mobileavailability(){
		if($this->request->getData['vendorId'] !='' && $this->request->getData['openday'] !='' && $this->request->getData['mobileprice'] !=''){
			$this->loadModel('Mobileavailability');
			//echo "<pre>";
			//print_r($this->request->getData);//die;
			$vendor = $this->Mobileavailability->find()->where(['Mobileavailability.userId'=>$this->request->getData['vendorId']])->first();
			if(@$vendor->id == ''){
				$vendor = $this->Mobileavailability->newEntity();
			}
			$DATA['userId'] 		= $this->request->getData['vendorId'];
			$DATA['mobileprice'] 	= @$this->request->getData['mobileprice'];
			
			$exp = explode(",", $this->request->getData['openday']);
			$DATA['MON'] = 0;
			$DATA['TUE'] = 0;
			$DATA['WED'] = 0;
			$DATA['THU'] = 0;
			$DATA['FRI'] = 0;
			$DATA['SAT'] = 0;
			$DATA['SUN'] = 0;
			foreach($exp as $val){
				if(strtolower($val) == 'mon'){
					$DATA['MON'] = 1;
				}elseif(strtolower($val) == 'tue'){
						$DATA['TUE'] = 1;
				}elseif(strtolower($val) == 'wed'){
						$DATA['WED'] = 1;
				}elseif(strtolower($val) == 'thu'){
						$DATA['THU'] = 1;
				}elseif(strtolower($val) == 'fri'){
						$DATA['FRI'] = 1;
				}elseif(strtolower($val) == 'sat'){
						$DATA['SAT'] = 1;
				}elseif(strtolower($val) == 'sun'){
						$DATA['SUN'] = 1;
				}
			}
			
			$vendor = $this->Mobileavailability->patchEntity($vendor, $DATA, ['validate' => false]);
			$vendor['created'] = date('Y-m-d H:i:s');
			$vendor['modified'] = date('Y-m-d H:i:s');
			$this->Mobileavailability->save($vendor);
			$response['status'] = 'Success';
			$response['msg'] = 'Mobile availability updated successfully';	
				
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'vendorId, openday and mobileprice are required';	
		}
		return 	$response;
	}

	function mobileavailability_get(){
		$this->loadModel('Mobileavailability');
		$userTYPE = (@$this->request->getData['userType']) ? @$this->request->getData['userType'] : 2;
		if($this->request->getData['vendorId'] !=''){
			$vendor = $this->Mobileavailability->find()->where(['Mobileavailability.userId'=>$this->request->getData['vendorId']])->first();
			if(@$vendor !=''){
				$DATA['id'] 						= $vendor->id;
				$DATA['vendorId'] 					= $vendor->userId;
				$DATA['mobileprice'] 				= $vendor->mobileprice;
				$DAY = array();
				if($vendor->MON == 1){
					array_push($DAY,"Mon");
				}
				if($vendor->TUE == 1){
					array_push($DAY,"Tue");
				}
				if($vendor->WED == 1){
					array_push($DAY,"Wed");
				}
				if($vendor->THU == 1){
					array_push($DAY,"Thu");
				}
				if($vendor->FRI == 1){
					array_push($DAY,"Fri");
				}
				if($vendor->SAT == 1){
					array_push($DAY,"Sat");
				}
				if($vendor->SUN == 1){
					array_push($DAY,"Sun");
				}
				$DATA['openday'] = implode(",", $DAY);
				
				$response['status'] = 'Success';
				$response['data'] = $DATA;	
			}else{
				$response['status'] = 'Success';
				$response['msg'] = 'Please fill all the fields.';
				$response['data'] = array();
			}
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'vendorId is required';
		}
		return 	$response;
	}


	
	function setting(){
		if($this->request->getData['vendorId'] !='' && $this->request->getData['openday'] !='' && $this->request->getData['UTYPE'] !='' && $this->request->getData['price'] !='' && $this->request->getData['time_slot_duration'] !='' ){
			$this->loadModel('Videochatavailability');
			//echo "<pre>";
			//print_r($this->request->getData);//die;
			$this->Videochatavailability->deleteAll(['Videochatavailability.userId'=>$this->request->getData['vendorId'],'Videochatavailability.UTYPE'=>$this->request->getData['UTYPE']]);
			$DATA['UTYPE'] 			= $this->request->getData['UTYPE'];
			$DATA['userId'] 		= $this->request->getData['vendorId'];
			$DATA['price'] 			= @$this->request->getData['price'];
			$DATA['total_price'] 	= ((@$this->request->getData['price']*116)/100);
			$DATA['time_slot_duration']= @$this->request->getData['time_slot_duration'];
			
			$j_Data = json_decode($this->request->getData['openday'], true);
			//print_r($j_Data);
			foreach($j_Data as $val_data){
				$DATA['startTime'] 		= date('H:i',strtotime($val_data['startTime']));
				$DATA['endTime'] 		= date('H:i',strtotime($val_data['endTime']));
				$exp = explode(",", $val_data['day']);
				$DATA['MON'] = 0;
				$DATA['TUE'] = 0;
				$DATA['WED'] = 0;
				$DATA['THU'] = 0;
				$DATA['FRI'] = 0;
				$DATA['SAT'] = 0;
				$DATA['SUN'] = 0;
				foreach($exp as $val){
					if(strtolower($val) == 'mon'){
						$DATA['MON'] = 1;
					}elseif(strtolower($val) == 'tue'){
							$DATA['TUE'] = 1;
					}elseif(strtolower($val) == 'wed'){
							$DATA['WED'] = 1;
					}elseif(strtolower($val) == 'thu'){
							$DATA['THU'] = 1;
					}elseif(strtolower($val) == 'fri'){
							$DATA['FRI'] = 1;
					}elseif(strtolower($val) == 'sat'){
							$DATA['SAT'] = 1;
					}elseif(strtolower($val) == 'sun'){
							$DATA['SUN'] = 1;
					}
				}
				$vendor = $this->Videochatavailability->newEntity();
				$vendor = $this->Videochatavailability->patchEntity($vendor, $DATA, ['validate' => false]);
				$vendor['created'] = date('Y-m-d H:i:s');
				$vendor['modified'] = date('Y-m-d H:i:s');
				$this->Videochatavailability->save($vendor);
			}
			
			$response['status'] = 'Success';
			$response['msg'] = 'Setting updated successfully';	
				
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'vendorId, openday, UTYPE, time_slot_duration, and price are required';	
		}
		return 	$response;
	}
			
	function getsetting(){
		$this->loadModel('Videochatavailability');
		$userTYPE = (@$this->request->getData['userType']) ? @$this->request->getData['userType'] : 2;
		if($this->request->getData('vendorId') !=''){
			$vendor = $this->Videochatavailability->find()->where(['Videochatavailability.UTYPE'=>$userTYPE,'Videochatavailability.userId'=>$this->request->getData('vendorId')])->toArray();
			if(@$vendor){
				$DATA['id'] 						= $vendor[0]->id;
				$DATA['vendorId'] 					= $vendor[0]->userId;
				$DATA['UTYPE'] 						= $vendor[0]->UTYPE;
				
				$DATA['time_slot_duration'] 		= (@$vendor[0]->time_slot_duration) ? @$vendor[0]->time_slot_duration : '';
				$DATA['price'] 						= (@$vendor[0]->price) ? @$vendor[0]->price : '';
						
				$new_day = array();
				$final = array();
				$k = 0;
				//pr($vendor);
				foreach($vendor as $valll){
					$DAY = array();
					if($valll->MON == 1){
						array_push($DAY,"Mon");
					}
					if($valll->TUE == 1){
						array_push($DAY,"Tue");
					}
					if($valll->WED == 1){
						array_push($DAY,"Wed");
					}
					if($valll->THU == 1){
						array_push($DAY,"Thu");
					}
					if($valll->FRI == 1){
						array_push($DAY,"Fri");
					}
					if($valll->SAT == 1){
						array_push($DAY,"Sat");
					}
					if($valll->SUN == 1){
						array_push($DAY,"Sun");
					}
					$new_day[$k]['day'] = implode(",", $DAY);
					$new_day[$k]['startTime'] 	= date('h:i A',strtotime($valll->startTime));
					$new_day[$k]['endTime'] 	= date('h:i A',strtotime($valll->endTime));
					//$final[$k] = $new_day;
					$k++;
				}
				//pr($new_day); die;
				$DATA['openday'] = json_encode($new_day);
					
				$response['status'] = 'Success';
				$response['data'] = $DATA;	
			}else{
				$response['status'] = 'Success';
				$response['msg'] = 'Please fill all the fields.';
				$response['data'] = array();
			}
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'vendorId is required';
		}
		return 	$response;
	}
			
			//function vendorslot($vendorID=NULL, $DATE=NULL){
			//	$this->loadModel('Settings');
			//			$vendorId = ($vendorID) ? $vendorID : $this->request->getData('vendorId');
			//			$Date = ($DATE) ? $DATE : $this->request->getData('date');
			//			$DAY = strtoupper(date('D', strtotime($Date)));
			//			$slot = array();
			//			//$vendor = $this->Setting->find('first',array('conditions'=>array('Setting.userId'=>$vendorId, 'Setting.'.$DAY=>1)));
			//			$vendor = $this->Settings->find()->where(['Settings.userId'=>$vendorId, 'Settings.'.$DAY=>1])->first();
			//			if(@$vendor->id !=''){
			//					$totalSlote = explode(":", $vendor->endTime)[0] - explode(":", $vendor->startTime)[0];
			//					$j=0;
			//					for($i=1; $i<= $totalSlote; $i++ ){
			//							$STAT = date('h:i A',strtotime($vendor->startTime. " + ".$j."hour "));
			//							$END = date('h:i A',strtotime($vendor->startTime. " + ".$i."hour "));
			//							$slot[$i]['slot'] 		= $STAT."-".$END;
			//							$BST = strtotime($vendor->brakeStartTime);
			//							$BED = strtotime($vendor->breakEndTime);
			//							
			//							$CHEEKSLOT = date('H:i',strtotime($vendor->startTime. " + ".$j."hour "))."-".date('H:i',strtotime($vendor->startTime. " + ".$i."hour "));
			//							$status = $this->chkSlotbook($vendorId, $CHEEKSLOT,$Date);
			//							if($BST >= strtotime($STAT) && $BST <= strtotime($END)){
			//									$slot[$i]['status'] = 'Break Time';
			//							}else{
			//									$slot[$i]['status'] = $status;
			//							}
			//							
			//							$j++;
			//					}
			//					$response['status'] = 'Success';
			//					$response['data'] = array_values($slot);
			//			}else{
			//				$vendor = $this->Settings->find()->where(['Settings.userId'=>$vendorId])->first();
			//				if(@$vendor->id !=''){
			//						$response['status'] = 'Success';
			//						$response['msg'] = 'Off day for this vendor.';
			//						$response['data'] = array();
			//				}else{
			//					$response['status'] = 'Success';
			//					$response['msg'] = 'Off day for this vendor.';
			//					$slot[0]['slot'] 		= "10:00 AM - 11:00 AM";
			//					$slot[0]['status'] = 'Available';
			//					
			//					$slot[1]['slot'] 		= "11:00 AM - 12:00 PM";
			//					$slot[1]['status'] = 'Available';
			//					
			//					$slot[2]['slot'] 		= "12:00 PM - 01:00 PM";
			//					$slot[2]['status'] = 'Available';
			//					
			//					$slot[3]['slot'] 		= "01:00 PM - 02:00 PM";
			//					$slot[3]['status'] = 'Available';
			//					
			//					$slot[4]['slot'] 		= "02:00 PM - 03:00 PM";
			//					$slot[4]['status'] = 'Available';
			//					
			//					$slot[5]['slot'] 		= "03:00 PM - 04:00 PM";
			//					$slot[5]['status'] = 'Available';
			//					
			//					$slot[6]['slot'] 		= "04:00 PM - 05:00 PM";
			//					$slot[6]['status'] = 'Available';
			//					
			//					$slot[7]['slot'] 		= "05:00 PM - 06:00 PM";
			//					$slot[7]['status'] = 'Available';
			//					$slot[8]['slot'] 		= "06:00 PM - 07:00 PM";
			//					$slot[8]['status'] = 'Available';
			//					$response['data'] = array_values($slot);
			//				}
			//			}
			//			return 	$response;
			//}
			
			function vendorslot($vendorID=NULL, $DATE=NULL,$userType=NULL){
				$this->loadModel('Settings');
				$vendorId = ($vendorID) ? $vendorID : $this->request->getData('vendorId');
				$UserTYPE = ($userType) ? $userType : @$this->request->getData('userType');
				if($UserTYPE ==''){
					$UserTYPE = 2;
				}
				$Date = ($DATE) ? $DATE : $this->request->getData('date');
				$DAY = strtoupper(date('D', strtotime($Date)));
				$slot = array();
				//$vendor = $this->Setting->find('first',array('conditions'=>array('Setting.userId'=>$vendorId, 'Setting.'.$DAY=>1)));
				$vendor = $this->Settings->find()->where(['Settings.userId'=>$vendorId, 'Settings.userType'=>$UserTYPE,'Settings.'.$DAY=>1])->first();
				if(@$vendor->id !=''){
					$totalSlote = explode(":", @$vendor->endTime)[0] - explode(":", @$vendor->startTime)[0];
					$j=0;$k=30;
					for($i=1; $i<= $totalSlote*2; $i++ ){
							$STAT = date('h:i A',strtotime($vendor->startTime. " + ".$j."minutes "));
							$END = date('h:i A',strtotime($vendor->startTime. " + ".$k."minutes "));
						if(strtotime($END) <= strtotime($vendor->endTime)){
							$slot[$i]['slot'] 		= $STAT."-".$END;
							$BST = strtotime($vendor->brakeStartTime);
							$BED = strtotime($vendor->breakEndTime);
							
							$CHEEKSLOT = date('H:i',strtotime($vendor->startTime. " + ".$j."minutes "))."-".date('H:i',strtotime($vendor->startTime. " + ".$k."minutes "));
							$status = $this->chkSlotbook($vendorId, $CHEEKSLOT, $Date);
							if((strtotime($STAT) >= $BST && strtotime($STAT) < $BED) || (strtotime($END) < $BST &&  strtotime($END) >= $BED )){
							//if(($BST >= strtotime($STAT) && $BST < strtotime($END)) || ( $BED > strtotime($STAT) &&  $BED <= strtotime($END))){
								$slot[$i]['status'] = 'Break Time';
							}else{
								$slot[$i]['status'] = $status;
							}
							
							$j =$j+30;
							$k = $k+30;
						}
					}
					$new_time_zone_Slot = array();
					foreach($slot as $KEY=>$Veb){
						$verdor_sign = substr(@$vendor->current_time_zone, 0, 1);
						$customer_sign = substr(@$this->request->getData('current_time_zone'), 0, 1);
						$Vendor_time_Zone = explode(":",substr(@$vendor->current_time_zone,1));
						$customer_time_Zone = explode(":",substr(@$this->request->getData('current_time_zone'),1));
						$vendor_total_minute = $Vendor_time_Zone[0] * 60 + $Vendor_time_Zone[1];
						$customer_total_minute = $customer_time_Zone[0] *60 + $customer_time_Zone[1];
						if($verdor_sign == $customer_sign){
							$Final = $customer_total_minute - $vendor_total_minute;
							$sign = '-';
							if($customer_total_minute >= $vendor_total_minute){
								$sign = '+';
							}
							//$time = $sign.intdiv($Final, 60).':'. ($Final - (intdiv($Final, 60) *60));
						}else{
							$Final = $customer_total_minute + $vendor_total_minute;
							$sign = $customer_sign;
							//$time = $sign.intdiv($Final, 60).':'. ($Final - (intdiv($Final, 60) *60));
						}
						$exp_slot = explode("-",$Veb['slot']);
						$current_date_time_start = $this->request->getData('date').' '.date('H:i',strtotime($exp_slot[0])).':00';
						$current_date_time_end = $this->request->getData('date').' '.date('H:i',strtotime($exp_slot[1])).':00';
						$CAL = abs($Final*60);
						if($sign == '+'){
							$NOW_start = strtotime($current_date_time_start)+$CAL;
							$NOW_end = strtotime($current_date_time_end)+$CAL;
						}else{
							$NOW_start = (strtotime($current_date_time_start)-$CAL);
							$NOW_end = strtotime($current_date_time_end)-$CAL;
						}
						if(date('Y-m-d',$NOW_start) ==  $this->request->getData('date')){
							$slot[$KEY]['converted_slot'] =  date('h:i A',$NOW_start)."-".date('h:i A',$NOW_end);
						}else{
							$slot[$KEY]['converted_slot'] =  'Booked';
						}
						

					}

					$response['status'] = 'Success';
					$response['current_time_zone_doctor'] = @$vendor->current_time_zone;
					$response['zone_doctor'] = @$vendor->zone;
					$response['data'] = array_values($slot);
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'Please select another date to book an appointment.';
					//$vendor = $this->Settings->find()->where(['Settings.userId'=>$vendorId,'Settings.userType'=>$UserTYPE,])->first();
					//if(@$vendor->id !=''){
					//		$response['status'] = 'Success';
					//		$response['msg'] = 'Off day for this vendor.';
					//		$response['data'] = array();
					//}else{
					//	$response['status'] = 'Success';
					//	$response['msg'] = 'Off day for this vendor.';
					//	$slot[0]['slot'] 		= "10:00 AM - 11:00 AM";
					//	$slot[0]['status'] = 'Available';
					//	
					//	$slot[1]['slot'] 		= "11:00 AM - 12:00 PM";
					//	$slot[1]['status'] = 'Available';
					//	
					//	$slot[2]['slot'] 		= "12:00 PM - 01:00 PM";
					//	$slot[2]['status'] = 'Available';
					//	
					//	$slot[3]['slot'] 		= "01:00 PM - 02:00 PM";
					//	$slot[3]['status'] = 'Available';
					//	
					//	$slot[4]['slot'] 		= "02:00 PM - 03:00 PM";
					//	$slot[4]['status'] = 'Available';
					//	
					//	$slot[5]['slot'] 		= "03:00 PM - 04:00 PM";
					//	$slot[5]['status'] = 'Available';
					//	
					//	$slot[6]['slot'] 		= "04:00 PM - 05:00 PM";
					//	$slot[6]['status'] = 'Available';
					//	
					//	$slot[7]['slot'] 		= "05:00 PM - 06:00 PM";
					//	$slot[7]['status'] = 'Available';
					//	$slot[8]['slot'] 		= "06:00 PM - 07:00 PM";
					//	$slot[8]['status'] = 'Available';
					//	$response['data'] = array_values($slot);
					//}
				}
				return 	$response;
			}
			
			
		function chkSlotbook($vendorId,$slot,$Date){
			$this->loadModel('Bookings');
			$cond[] = ['Bookings.vendorId' => $vendorId];
			$cond[] = ['Bookings.bookingDate' => $Date];
			$cond[] = ['Bookings.slotTime' => $slot];
			$cond[] = ['Bookings.status' => 1];
			//echo "<pre>"; print_r($cond); die;				
			$booked = $this->Bookings->find()->where($cond)->count();
			if($booked > 0){
				$status = "Booked";
			}else{
				$status = "Available";
			}
			return $status; 
		}
		
		
	private function login($slug=NULL){
		if($this->request->getData()){
			//pr($this->request->getData); die;
			//$this->loadModel('Users');
			//$usr = $this->Users->find()->where(['Users.email' => $this->request->getData('email')])->first();
			$usr = $this->fetchTable('Users')->find()->where(['OR'=>[['Users.email' => $this->request->getData('email')],['Users.contactNumber' => $this->request->getData('email')]]])->first();
				if(!empty($usr)){
					$passwordHasher = new DefaultPasswordHasher();
						$check = $passwordHasher->check(trim($this->request->getData('password')),$usr->password);
						if($check && $usr->status == 1){
								//$userdetail['FreedomUsers'] = $usr;
								//$this->request->session()->write($userdetail);
								$usr = $this->Users->patchEntity($usr, $this->request->getData(), ['validate' => false]);
								$this->Users->save($usr);
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
	
	
	function profile($userId = NULL){
			$this->loadModel('Users');
			$this->loadModel('Carts');
			$userID = ($userId) ? $userId : $this->request->getData('userId');
			
			$userD = $this->Users->find()->where(['Users.id' => $userID])->contain(['Countries','States','Cities'])->first();
			//$userD = $this->Users->find()->where(['Users.id' => $userID])->contain(['Countries', 'States'])->first();
			//pr($userD); die;
			if($userD){
				$UserDetail['userId'] 				= ($userD->id) ? $userD->id : '';
				$UserDetail['firstName'] 			= ($userD->firstName) ? $userD->firstName : '';
				$UserDetail['lastName'] 			= ($userD->lastName) ? $userD->lastName : '';
				$UserDetail['email'] 				= ($userD->email) ? $userD->email : '';
				$UserDetail['role'] 				= ($userD->role) ? $userD->role : '';
				$UserDetail['address'] 				= ($userD->address) ? $userD->address : '';
				$UserDetail['gender'] 				= ($userD->gender) ? $userD->gender : '';
				$UserDetail['dob'] 					= ($userD->dob) ? $userD->dob : '';
				$UserDetail['cityId'] 				= (@$userD->cityId) ? @$userD->cityId : '';
				$UserDetail['cityname'] 			= (@$userD->city->name) ? @$userD->city->name : '';
				$UserDetail['stateId'] 				= (@$userD->stateId) ? @$userD->stateId : '';
				$UserDetail['stateName'] 			= (@$userD->state->name) ? @$userD->state->name : '';
				$UserDetail['countryName'] 			= (@$userD->country->name) ? @$userD->country->name : '';
				$UserDetail['countryId'] 			= (@$userD->countryId) ? @$userD->countryId : '';
				$UserDetail['zipCode'] 				= ($userD->zipCode) ? $userD->zipCode : '';
				$UserDetail['contactNumber'] 		= ($userD->contactNumber) ? $userD->contactNumber :'';
				$UserDetail['image'] 				= ($userD->profile_picture) ? $this->profilePath.$userD->profile_picture : '';
				if(strpos($userD->profile_picture, "http") !== false){
					$UserDetail['image'] = $userD->profile_picture;
				}
				$UserDetail['BImage'] 				= ($userD->BImage) ? $this->profilePath.$userD->BImage : '';
				$UserDetail['profile_ID_image'] 	= ($userD->profile_ID_image) ? $this->profilePath.$userD->profile_ID_image : '';
				$UserDetail['verify_Profile_id'] 			= ($userD->verify_Profile_id) ? $userD->verify_Profile_id : 0;
				$UserDetail['VFirstName'] 			= ($userD->VFirstName) ? $userD->VFirstName : '';
				$UserDetail['VLastName'] 			= ($userD->VLastName) ? $userD->VLastName : '';
				$UserDetail['VLicenseNo'] 			= ($userD->VLicenseNo) ? $userD->VLicenseNo : '';
				$UserDetail['VState'] 				= ($userD->VState) ? $userD->VState : '';
				$UserDetail['VBusinessName'] 		= ($userD->VBusinessName) ? $userD->VBusinessName : '';
				$UserDetail['VTaxID'] 				= ($userD->VTaxID) ? $userD->VTaxID : '';
				$UserDetail['VBusinessAddress'] 	= ($userD->VBusinessAddress) ? $userD->VBusinessAddress : '';
				$UserDetail['VBSuite'] 				= ($userD->VBSuite) ? $userD->VBSuite : '';
				
				$UserDetail['Vcity'] 				= ($userD->Vcity) ? $userD->Vcity : '';
				$UserDetail['VAstate'] 				= ($userD->VAstate) ? $userD->VAstate : '';
				$UserDetail['VZipcode'] 			= ($userD->VZipcode) ? $userD->VZipcode : '';
				$UserDetail['VPhone'] 				= ($userD->VPhone) ? $userD->VPhone : '';
				$UserDetail['VEmail'] 				= ($userD->VEmail) ? $userD->VEmail : '';
				$UserDetail['mobileClicnic']		= ($userD->mobileClicnic) ? $userD->mobileClicnic : '';
				$UserDetail['FixedTraditional']		= ($userD->FixedTraditional) ? $userD->FixedTraditional : '';
				$UserDetail['Freelance'] 			= ($userD->Freelance) ? $userD->Freelance : '';
				$UserDetail['YearInBusiness'] 		= ($userD->YearInBusiness) ? $userD->YearInBusiness : '';
				$UserDetail['typeOfPets'] 			= ($userD->typeOfPets) ? $userD->typeOfPets : '';
				
				$UserDetail['TypeOfService'] 		= ($userD->TypeOfService) ? $userD->TypeOfService : '';
				$UserDetail['Specialization']		= ($userD->Specialization) ? $userD->Specialization : '';
				$UserDetail['digonasticLab'] 		= ($userD->digonasticLab) ? $userD->digonasticLab : '';
				$UserDetail['Imaging'] 				= ($userD->Imaging) ? $userD->Imaging : '';
				$UserDetail['other'] 				= ($userD->other) ? $userD->other : '';
				$UserDetail['categoryId'] 			= ($userD->categoryId) ? $userD->categoryId : '';
				$UserDetail['biography'] 			= ($userD->biography) ? $userD->biography : '';
				$UserDetail['estimatePrice'] 		= ($userD->estimatePrice) ? $userD->estimatePrice : '';
				$UserDetail['AccountName'] 				= ($userD->AccountName) ? $userD->AccountName : '';
				$UserDetail['accountNo'] 			= ($userD->accountNo) ? $userD->accountNo : '';
				$UserDetail['RoutingNo'] 			= ($userD->RoutingNo) ? $userD->RoutingNo : '';
				$UserDetail['swiftNumber'] 			= ($userD->swiftNumber) ? $userD->swiftNumber : '';
				$UserDetail['BankName'] 			= ($userD->BankName) ? $userD->BankName : '';
				$UserDetail['accountType'] 			= ($userD->accountType) ? $userD->accountType : '';
				
				$UserDetail['device'] 				= ($userD->device) ? $userD->device : '';
				$UserDetail['deviceToken'] 			= ($userD->deviceToken) ? $userD->deviceToken : '';
				$UserDetail['firebaseId'] 			= ($userD->firebaseId) ? $userD->firebaseId : '';
				$UserDetail['socialId'] 			= ($userD->socialId) ? $userD->socialId : '';
				$UserDetail['socialType'] 			= ($userD->socialType) ? $userD->socialType : '';
				
				$UserDetail['ExpiryStatus'] 		= (date('Y-m-d', strtotime($userD->ExpiryDate)) > date('Y-m-d')) ? 1 : 0; //0=EXPIRED
				$UserDetail['ExpiryDate'] 			= ($userD->ExpiryDate) ? date('Y-m-d', strtotime($userD->ExpiryDate)) : '';
				$UserDetail['subscribeDate'] 		= ($userD->subscribeDate) ? date('Y-m-d H:i:s', strtotime($userD->subscribeDate)) :'';
				$UserDetail['stripeSubscriptionId'] = ($userD->stripeSubscriptionId) ? $userD->stripeSubscriptionId :'';
				$UserDetail['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.userId'=>$userD->id])->count();
				
				$response['status'] = 'success';
				$response['data'] = $UserDetail;
				
			}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'UserId required';
			}
			
			return  $response;
	}
	
	function termAndConditions(){
		$this->loadModel('Pages');
		
		$pages = $this->Pages->find()->Where(['Pages.id' => 5])->first();
		$response['status'] = 'success';
		$response['msg'] = $pages->content;
		return  $response;
	}
	
	function privacypolicy(){
		$this->loadModel('Pages');
		
		$pages = $this->Pages->find()->Where(['Pages.id' => 4])->first();
		$response['status'] = 'success';
		$response['msg'] = $pages->content;
		return  $response;
	}
	
	private function addcontact(){
		$this->loadModel('Users');
		$this->loadModel('Contacts');
		if($this->request->getData('name') !=''){
				$users = $this->Contacts->newEntity();
				$users = $this->Contacts->patchEntity($users, $this->request->getData, ['validate' => false]);
				$users['status'] = 1;
				$users['created'] = date('Y-m-d H:i');
				$fileName1 = '';
				$fileName2 = '';
				
				if(@$_FILES['image_1']['name'] !=''){
					$fileName1=time().stripslashes($_FILES['image_1']['name']);
					$fileName1= str_replace(" ","",$fileName1);
					$users['image_1']=$fileName1;
				}
				if(@$_FILES['Bimage_2']['name'] !=''){
					$fileName2=time().stripslashes($_FILES['image_2']['name']);
					$fileName2= str_replace(" ","",$fileName2);
					$users['image_2']=$fileName2;
				}
				
				//$path = WWW_ROOT . 'img/uploads/feature/original/';
				if ($this->Contacts->save($users)) {
						if(@$_FILES['image_1']['name'] !='' && $fileName1 !=''){
								$target = WWW_ROOT.'/img/uploads/contact/'.$fileName1;
								$source=$_FILES['image_1']['tmp_name'];
								move_uploaded_file($source,$target);
						}
						if(@$_FILES['image_2']['name'] !='' && $fileName2 !=''){
								$target = WWW_ROOT.'/img/uploads/contact/'.$fileName2;
								$source=$_FILES['image_2']['tmp_name'];
								move_uploaded_file($source,$target);
						}
							$response['status'] = 'success';
							$response['msg'] = 'Data save successfully.';
							
							$res = $this->details($users->id);
							$response['data'] = $res['data'];
				}
			
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'name is required';
		}
		return  $response;
	}
	
	
	private function addproduct(){
		$this->loadModel('Products');
		if($this->request->getData['SKU'] !='' && $this->request->getData['userId'] !='' && $this->request->getData['categoryId'] !='' && $this->request->getData['productName'] !='' && $this->request->getData['quantity'] !=''){
				@$PreV = $this->Products->find()->where(['Products.SKU'=>$this->request->getData['SKU']])->first();
				if(@$PreV->id ==''){
						$products = $this->Products->newEntity();
						$products = $this->Products->patchEntity($products, $this->request->getData, ['validate' => false]);
						$products['status'] = 1;
						$products['created'] = date('Y-m-d H:i');
						$fileName1 = '';
						$fileName2 = '';
						
						if(@$_FILES['image']['name'] !=''){
							$fileName1=time().stripslashes($_FILES['image']['name']);
								$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
								$rplc =['','',"","","","","","","","","","","","",""];
								$fileName1 = str_replace($str,$rplc,$fileName1);
							$products['image']=$fileName1;
						}
						
						//$path = WWW_ROOT . 'img/uploads/feature/original/';
						if ($this->Products->save($products)) {
								if(@$_FILES['image']['name'] !='' && $fileName1 !=''){
										$target = WWW_ROOT.'/img/uploads/products/'.$fileName1;
										$source=$_FILES['image']['tmp_name'];
										move_uploaded_file($source,$target);
								}
								
								$response['status'] = 'success';
								$response['msg'] = 'Data save successfully.';
								
								$res = $this->productlist($this->request->getData['userId']);
								$response['data'] = $res['data'];
						}
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'SKU already exist.';
				}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'SKU, Product name, category, price and quantity are required';
		}
		return  $response;
	}
	
	private function editproduct(){
		$this->loadModel('Products');
		if($this->request->getData('productId') !=''){
			$this->loadModel('Users');
			$products = $this->Products->find()->where(['Products.id' => $this->request->getData('productId')])->first();
			if(!empty($products)){
				
				$fileName = '';
				if(@$_FILES['image']['name'] !='' && @$_FILES['image']['error'] == 0){
						$fileName=time().stripslashes($_FILES['image']['name']);
						$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
						$rplc =['','',"","","","","","","","","","","","",""];
						$fileName = str_replace($str,$rplc,$fileName);
						$this->request->getData['image']=$fileName;
						$url = $this->productPath.$products->image;
						@unlink($url);
				}else{
					unset($this->request->getData['image']);
				}

				$products = $this->Products->patchEntity($products, $this->request->getData, ['validate' => false]);
				//$path = WWW_ROOT . 'img/uploads/feature/original/';
				if ($this->Products->save($products)) {
						if(@$_FILES['image']['name'] !='' && $fileName !=''){
							$target = WWW_ROOT.'/img/uploads/products/'.$fileName;
							$source=$_FILES['image']['tmp_name'];
							move_uploaded_file($source,$target);
						}
						$response['status'] = 'success';
						$response['msg'] = 'Data save successfully.';
							
						//$res = $this->productlist($products->userId);
						$res = $this->productdetails($products->id,$products->userId);
						$response['data'] = $res['data'];
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'productId does not exist';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'productId is required';
		}
		return  $response;
	}
	
		private function productlist($userId=NULL){
			$this->loadModel('Products');
			$this->loadModel('User');$this->loadModel('Carts'); $this->loadModel('Reviews');
			@$USERID = ($userId) ? $userId : $this->request->getData('userId');
			@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
				
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Products.id' => 'desc'],
				'page'=>$pageNo,
				'contain' => ['Users','Categories', 'Subcategories']
			];
			$keyword = $this->request->getData('keyword');
			$category = $this->request->getData('category');
			$subcategory = $this->request->getData('subcategory');
			$min_price = @$this->request->getData['min_price'];
			$max_price = @$this->request->getData['max_price'];

			if(@$this->request->getData['freestuff'] == 1){
				$condition[] = ['Products.unitPrice'=>0];
			}else{
				$condition[] = ['Products.unitPrice >'=>0];
			}

			if($min_price !=''){
				$condition[] = ['Products.specialPrice >='=>$min_price];
			}
			if($max_price !=''){
				$condition[] = ['Products.specialPrice <'=>$max_price];
			}

			if($USERID !='' && @$this->request->getData['ownlist'] == 1){
				$condition[] = ['Products.userId'=>$USERID];
			}else{
				$condition[] = ['Products.status'=>1];
			}
			
			if($category !=''){
				$condition[] = ['Products.categoryId'=>$category];
			}
			if($subcategory !=''){
				$condition[] = ['Products.subCategory'=>$subcategory];
			}
			if($keyword !=''){
				$condition[] = ['OR'=>[
									'Products.productName LIKE'=>$keyword.'%',
								]
							];
			}
			$userservices =array();    
			$query = $this->Products->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//echo "<pre>"; print_r($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['productId'] 			= ($val->id) ? $val->id : '';
				$Darray[$i]['productUserId'] 		= ($val->userId) ? $val->userId : '';
				$Darray[$i]['categoryId'] 			= ($val->categoryId) ? $val->categoryId : '';
				$Darray[$i]['categoryName'] 		= (@$val->category->name) ? @$val->category->name : '';
				$Darray[$i]['subcategoryId'] 		= ($val->subCategory) ? $val->subCategory : '';
				$Darray[$i]['subcategoryName'] 		= (@$val->subcategory->name) ? @$val->subcategory->name : '';
				$Darray[$i]['productName'] 			= ($val->productName) ? $val->productName : '';
				$Darray[$i]['image'] 				= ($val->image) ? $this->productPath.$val->image : '';
				$Darray[$i]['quantity'] 			= ($val->quantity) ? "$val->quantity" : "0";
				$Darray[$i]['unitPrice'] 			= ($val->unitPrice) ? "$val->unitPrice" : "0.00";
				$Darray[$i]['description'] 			= ($val->description) ? $val->description : '';
				$Darray[$i]['SKU'] 					= ($val->SKU) ? $val->SKU : '';
				$Darray[$i]['status'] 				= ($val->status) ? $val->status : 0;
				$Darray[$i]['shippingAmount'] 		= ($val->shippingAmount) ? "$val->shippingAmount" : "0.00";
				$Darray[$i]['specialPrice'] 		= ($val->specialPrice) ? "$val->specialPrice" : "0.00";
				$Darray[$i]['AVGRating'] 			= ($val->AVGRating) ? $val->AVGRating : 0;
				$Darray[$i]['review_count'] 		= @$this->Reviews->find()->where(['Reviews.productId'=>$val->id])->count();
				
				$Darray[$i]['userName'] 			= (@$val->user->fullName) ? @$val->user->fullName : '';
				$Darray[$i]['userLastName'] 		= (@$val->user->lastName) ? @$val->user->lastName : '';
				$Darray[$i]['userEmail'] 			= (@$val->user->email) ? @$val->user->email : '';
				$Darray[$i]['userPhone'] 			= (@$val->user->contactNumber) ? @$val->user->contactNumber : '';
				$Darray[$i]['SellerCompanyName'] 	= ($val->SellerCompanyName) ? $val->SellerCompanyName : '';
				$carts_d = $this->Carts->find()->where(['Carts.productId'=>$val->id,'Carts.userId'=>$USERID,'Carts.orderId is'=>null])->first();
				if(@$carts_d){
					$Darray[$i]['carts'] 				= $carts_d;
				}
				

				//$Darray[$i]['image'] 										= ($val->image) ? $this->productpath.$val->image : '';
				//$Darray[$i]['modified'] 							= ($val->listing->EXP) ? $val->listing->EXP : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
			//$response['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.userId'=>$userD->id])->count();
				
			return  $response;
		}
		
		private function productdetails($productId=NULL, $userId=NULL){
				$this->loadModel('Products');
				$this->loadModel('Carts');
				$this->loadModel('User');
				$this->loadModel('Reviews');
				@$ProductID = ($productId) ? $productId : $this->request->getData('productId');
				@$USERID = ($userId) ? $userId : $this->request->getData('userId');
				$Darray = array();
					if($ProductID){
							$val = $this->Products->find()->where(['Products.id'=>$ProductID])->contain(['Users','Categories', 'Subcategories'])->first();
							//echo "<pre>"; print_r($userservices); die;
								$Darray['productId'] 					= ($val->id) ? $val->id : '';
								$Darray['productUserId'] 				= ($val->userId) ? $val->userId : '';
								$Darray['categoryId'] 					= ($val->categoryId) ? $val->categoryId : '';
								$Darray['categoryName'] 				= (@$val->category->name) ? @$val->category->name : '';
								$Darray['subcategoryId'] 				= ($val->subCategory) ? $val->subCategory : '';
								$Darray['subcategoryName'] 				= (@$val->subcategory->name) ? @$val->subcategory->name : '';
								$Darray['productName'] 					= ($val->productName) ? $val->productName : '';
								$Darray['image'] 						= ($val->image) ? $this->productPath.$val->image : '';
								$Darray['quantity'] 					= ($val->quantity) ? $val->quantity : '0';
								$Darray['unitPrice'] 					= ($val->unitPrice) ? $val->unitPrice : '0.00';
								$Darray['description'] 					= ($val->description) ? $val->description : '';
								$Darray['SKU'] 							= ($val->SKU) ? $val->SKU : '';
								$Darray['userName'] 					= (@$val->user->firstName) ? @$val->user->firstName : '';
								$Darray['userLastName'] 				= (@$val->user->lastName) ? @$val->user->lastName : '';
								$Darray['userEmail'] 					= (@$val->user->email) ? @$val->user->email : '';
								$Darray['userPhone'] 					= (@$val->user->contactNumber) ? @$val->user->contactNumber : '';
								$Darray['SellerCompanyName']			= ($val->SellerCompanyName) ? $val->SellerCompanyName : '';
								$Darray['shippingAmount'] 				= ($val->shippingAmount) ? $val->shippingAmount : '0.00';
								$Darray['specialPrice'] 				= ($val->specialPrice) ? $val->specialPrice : '0.00';
								$Darray['status'] 						= ($val->status) ? $val->status : '0';
								$Darray['AVGRating'] 					= ($val->AVGRating) ? $val->AVGRating : 0;
								$Darray['TotalCartProduct'] 			= 0;
								$Darray['quantityInCart'] 				= 0;
								$Darray['cartId'] = '';
								if(@$USERID !=''){
									$CC = $this->Carts->find()->where(['Carts.orderId is'=>NULL,'Carts.userId'=>$USERID, 'Carts.productId'=>$ProductID])->first();
									$Darray['quantityInCart'] 	= (@$CC->quantity) ? @$CC->quantity : '0';
									$Darray['cartId'] 	= (@$CC->id) ? @$CC->id : '';
									$Darray['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.orderId is'=>null, 'Carts.userId'=>$USERID])->count();
								}

								$Darray['review_count'] = @$this->Reviews->find()->where(['Reviews.productId'=>$val->id])->count();
					}	
					$response['status'] = "success";
					$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
					
							//$response['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.userId'=>$userD->id])->count();
	
				return  $response;
		}
		
		function countcart(){
			$this->loadModel('Carts');
			@$USERID = ($userId) ? $userId : $this->request->getData('userId');
				$response['status'] = "success";
				$response['TotalCartProduct'] 	= $this->Carts->find('all')->where(['Carts.userId'=>$USERID])->count();
				return  $response;
		}
		
		private function productstatus(){
			$this->loadModel('Products');
			if($this->request->getData('productId') !='' && $this->request->getData('status') !=''){
				$this->loadModel('Users');
				$products = $this->Products->find()->where(['Products.id' => $this->request->getData('productId')])->first();
				if(!empty($products)){
					//pr($products); die;
					//@unset($this->request->getData('email'));
					$products = $this->Products->patchEntity($products, $this->request->getData, ['validate' => false]);
					//$path = WWW_ROOT . 'img/uploads/feature/original/';
					if ($this->Products->save($products)) {
						$response['status'] = 'success';
						$response['msg'] = 'Data save successfully.';
						$res = $this->productlist($products->userId);
						$response['data'] = $res['data'];
					}
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'productId does not exist';
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'productId is required';
			}
			return  $response;
		}
	
	
		function order(){
			$this->loadModel('Carts');
			$this->loadModel('Orderes');
			$this->loadModel('Products');
			$this->loadModel('Users');
			if($this->request->getData['userId'] !='' && $this->request->getData['productList'] !=''){
				//$users = $this->Users->find()->Where(['Users.id' => $this->request->getData('vendorId')])->first();
				//pr($this->request->getData);
				$carts_details = $this->Carts->find()->where(['Carts.userId'=>$this->request->getData['userId'], 'Carts.orderId is'=>null])->group('productOwner')->toArray();
				$arr = json_decode($this->request->getData['productList']);
				//pr($carts_details); die;
				
				$TOTALSHIPPING = 0;
				$TOTALAMOUNT = 0;
				$price = 0;
				foreach($arr as $val){
					$price 			= $val->price;
					$TOTALAMOUNT 	+= ($val->quantity * $val->price);
				}
				foreach($carts_details as $cart_d){
					$get_D = $this->Carts->find()->where(['Carts.productOwner'=>$cart_d->productOwner,'Carts.userId'=>$this->request->getData['userId'], 'Carts.orderId is'=>null])->toArray();
					//pr($get_D); die;
					$Quantity = 0; $price = 0; $T_amount = 0;
					foreach($get_D as $n){
						$Quantity += $n->quantity;
						$price += $n->unitPrice;
						$T_amount += $n->TotalPrice;
					}
					//pr($get_D); die;
					$DATA['order_number'] 			= 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));;
					$DATA['userId'] 				= $this->request->getData['userId'];
					
					$DATA['quantity'] 				= $Quantity;
					$DATA['price'] 					= @$price ;
					
					$DATA['TotalAmount'] 			= @$T_amount;
					$DATA['adminFees'] 				= @$this->request->getData['adminFees'];
					$DATA['paidAmount'] 			= @$this->request->getData['TotalAmount'];
					
					$DATA['created'] 				= date('Y-m-d H:i:s');
					$DATA['status'] 				= 1;
					$DATA['orderStatus']			= 1;
					$DATA['transactionID']			= @$this->request->getData['transactionID'];
					$DATA['paymentTrouugh']			= @$this->request->getData['paymentTrouugh'];

					$DATA['paymentStatus']			= 'paid';
					
					$DATA['shippingName']			= @$this->request->getData['shippingName'];
					$DATA['shippingAddress']		= @$this->request->getData['shippingAddress'];
					$DATA['ShippingCity']			= @$this->request->getData['ShippingCity'];
					$DATA['ShippingState']			= @$this->request->getData['ShippingState'];
					$DATA['shippingZipcode']		= @$this->request->getData['shippingZipcode'];
					$DATA['ShippingMobile']			= @$this->request->getData['ShippingMobile'];
					$DATA['current_time_zone']		= @$this->request->getData['current_time_zone'];
					
					$Userservices = $this->Orderes->newEntity();
					$Userservices = $this->Orderes->patchEntity($Userservices, $DATA, ['validate' => false]);
					$Userservices['created'] = date('Y-m-d H:i:s');
					$Userservices['status'] 	= '1';
					$Userservices['added_time'] 			= date('Y-m-d H:i:s',strtotime(@$this->request->getData['added_time']));
					$this->Orderes->save($Userservices);
				
					$articles = TableRegistry::get('Carts');
					$query = $articles->query();
					$query->update()
						->set(['orderId' => $Userservices->id])
						->where(['userId' => $this->request->getData['userId'], 'productOwner'=>@$cart_d->productOwner, 'orderId is' => null])
						->execute();

					foreach($get_D as $n){
						$products = $this->Products->get($n->productId);
						$final = (int)$products->quantity - (int)$n->quantity;
						$products->quantity = $final;
						$this->Products->save($products);
					}

				}


				
				//@$this->Carts->deleteAll(['Carts.userId' => $this->request->getData['userId']]);
					
				$buyer = $this->Users->find()->where(['Users.id'=>$this->request->getData['userId']])->first();
				$deviceToken = @$buyer->deviceToken;
				$body['message'] = 'Order Placed Successfully. Thank you for ordering. We received your order and will begin processing it soon. Your order information appears below.';
				$body['type'] = 'text';
				$this->sendNotificationOnAndroid($deviceToken,$body);
								
				$response['status'] = "success";
				$response['msg'] = 'Order placed successfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'userId, productList, shippingAmount and TotalAmount are required.';
			}
			return  $response;
		}
	
			
		function orderlist($userId=NULL, $type=NULL){
			$this->loadModel('Orderes');
			$VendorID = ($userId)? $userId : $this->request->getData['userId'];
			@$TYPE = ($type)? $type : @$this->request->getData['type'];
			@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
			if($VendorID !=''){
				$this->paginate = [
					'limit' => '10',//$limit,
					'order' => ['Orderes.id' => 'desc'],
					'page'=>$pageNo,
					//'group'=>['invoiceNo'],
					//'contain' => ['Users']
				];
				$keyword = $this->request->query('keyword');
				$condition[] = '';//['Invoices.status'=>1];
				
				if(@$this->request->getData['track_order'] !=''){
					$condition[] = ['Orderes.order_number'=>$this->request->getData['track_order']];
				}
				if($TYPE == 'OWNER'){
					$condition[] = ['Carts.productOwner'=>$VendorID,'Carts.orderId >'=>0];
					//$query = $this->Invoices->find('all')->where($condition);
				}else{
					$condition[] = ['Orderes.userId'=>$VendorID];
					//$query = $this->Invoices->find('all')->where($condition)->group('invoiceNo');
				}
				$query = $this->Orderes->find('all')->where($condition)->contain(['Carts' => ['Products' => ['Subcategories', 'Categories']]])->distinct(['Orderes.id']);
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
					$Darray[$i]['orderId'] 			= ($val->id) ? $val->id : '';
					$Darray[$i]['order_number'] 			= ($val->order_number) ? $val->order_number : '';
					$Darray[$i]['shippingDate'] 			= ($val->shippingDate) ? $val->shippingDate : '';
					$Darray[$i]['deliveryConfirm'] 			= ($val->deliveryConfirm) ? $val->deliveryConfirm : '';
					$Darray[$i]['orderStatus'] 			= ($val->orderStatus) ? $val->orderStatus : '';
					if(@$val->cart){
						$Darray[$i]['carts'] 			= ($val->cart) ? $val->cart :  NULL;
					}
					
					$Darray[$i]['userId'] 			= ($val->userId) ? $val->userId : '';
					$Darray[$i]['TotalAmount'] 			= ($val->TotalAmount) ? $val->TotalAmount : '';
					//$Darray[$i]['category'] 		= (@$val->cartlits->product->category) ? @$val->cartlits->product->category : array();
					//$Darray[$i]['subCategory'] 		= (@$val->cartlits->product->subcategory) ? @$val->cartlits->product->subcategory :  array();
					//$Darray[$i]['product'] 			= (@$val->cartlits) ? @$val->cartlits :  array();
					$Darray[$i]['created'] 			= ($val->created) ? date("Y-m-d H:i:s", strtotime($val->created)) : '';
					
					//$Darray[$i]['timezone'] = $this->timezone();
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
		
		
		
	
	function orderdetails($orderId =NULL, $userId=NULL){
		$this->loadModel('Carts');
		$VendorID = ($userId)? $userId : $this->request->getData['userId'];
		$OrderID = ($orderId)? $orderId : $this->request->getData['orderId'];
		//@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !='' && $OrderID !=''){
			    
			$this->loadModel('Orderes');
			$order_Details = $this->Orderes->find()->where(['Orderes.id'=>$OrderID])->first();
			$userservices = $this->Carts->find()->contain(['Products' => ['Subcategories', 'Categories']])
				->where([
					'Carts.orderId' => $OrderID,
					'OR' => [
						['Carts.userId' => $VendorID],
						['Carts.productOwner'=> $VendorID]
					]
				])->toArray();
			$Darray = array();
			$i=0;
			//pr($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['orderId'] 			= ($val->orderId) ? $val->orderId : '';
				$Darray[$i]['quantity'] 		= ($val->quantity) ? $val->quantity : '';
				$Darray[$i]['unitPrice'] 		= ($val->unitPrice) ? $val->unitPrice : '';
				$Darray[$i]['finalPrice'] 		= ($val->finalPrice) ? $val->finalPrice : '';
				$Darray[$i]['TotalPrice'] 		= ($val->TotalPrice) ? $val->TotalPrice : '';
				$Darray[$i]['product'] 			= ($val->product) ? $val->product : array();
				$Darray[$i]['userId'] 			= ($val->userId) ? $val->userId : '';
				$Darray[$i]['productOwner'] 			= ($val->productOwner) ? $val->productOwner : '';
				$Darray[$i]['shippingCode'] 			= ($val->shippingCode) ? $val->shippingCode : '';
				//TotalAmount
				//$Darray[$i]['category'] 		= (@$val->cart->product->category) ? @$val->cart->product->category : '';
				//$Darray[$i]['subCategory'] 		= (@$val->cart->product->subcategory) ? @$val->cart->product->subcategory : '';
				//$Darray[$i]['product'] 			= (@$val->cartlits) ? @$val->cartlits : '';

				// $Darray[$i]['userId'] 				= (@$val->user->id) ? @$val->user->id : '';
				// $Darray[$i]['userName'] 			= (@$val->user->fullName) ? @$val->user->fullName : '';
				// $Darray[$i]['userImage'] 			= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : '';
				// $Darray[$i]['userDiveceToken'] 		= (@$val->user->deviceToken) ? @$val->user->deviceToken : '';
				// $Darray[$i]['userEmail'] 			= (@$val->user->email) ? @$val->user->email : '';
				// $Darray[$i]['usercontactNumber'] 	= (@$val->user->contactNumber) ? @$val->user->contactNumber : '';
				
				$Darray[$i]['created'] 				= ($val->created) ? date("F jS, Y, g:i a", strtotime($val->created)) : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
			$response['order_data'] = (@$order_Details) ?  @$order_Details : array();
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId  and orderId are required.';
		}
		return  $response;
		
	}


	function ordertracke($orderId =NULL, $userId=NULL){
		$this->loadModel('Carts');
		$VendorID = ($userId)? $userId : $this->request->getData['userId'];
		$OrderID = ($orderId)? $orderId : $this->request->getData['order_number'];
		//@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		if($VendorID !='' && $OrderID !=''){
			    
			$this->loadModel('Orderes');
			$order_Details = $this->Orderes->find()->where(['Orderes.order_number'=>$OrderID])->first();
			if(@$order_Details->id !=''){
				$userservices = $this->Carts->find()->contain(['Products' => ['Subcategories', 'Categories']])
					->where([
						'Carts.orderId' => $order_Details->id,
						'OR' => [
							['Carts.userId' => $VendorID],
							['Carts.productOwner'=> $VendorID]
						]
					])->toArray();
				$Darray = array();
				$i=0;
				//pr($userservices); die;
				foreach($userservices as $val){
					$Darray[$i]['orderId'] 			= ($val->orderId) ? $val->orderId : '';
					$Darray[$i]['quantity'] 		= ($val->quantity) ? $val->quantity : '';
					$Darray[$i]['unitPrice'] 		= ($val->unitPrice) ? $val->unitPrice : '';
					$Darray[$i]['finalPrice'] 		= ($val->finalPrice) ? $val->finalPrice : '';
					$Darray[$i]['TotalPrice'] 		= ($val->TotalPrice) ? $val->TotalPrice : '';
					$Darray[$i]['product'] 			= ($val->product) ? $val->product : array();
					$Darray[$i]['userId'] 			= ($val->userId) ? $val->userId : '';
					$Darray[$i]['productOwner'] 	= ($val->productOwner) ? $val->productOwner : '';
					$Darray[$i]['shippingCode'] 	= ($val->shippingCode) ? $val->shippingCode : '';
					//TotalAmount
					//$Darray[$i]['category'] 		= (@$val->cart->product->category) ? @$val->cart->product->category : '';
					//$Darray[$i]['subCategory'] 	= (@$val->cart->product->subcategory) ? @$val->cart->product->subcategory : '';
					//$Darray[$i]['product'] 		= (@$val->cartlits) ? @$val->cartlits : '';

					// $Darray[$i]['userId'] 		= (@$val->user->id) ? @$val->user->id : '';
					// $Darray[$i]['userName'] 		= (@$val->user->fullName) ? @$val->user->fullName : '';
					// $Darray[$i]['userImage'] 		= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : '';
					// $Darray[$i]['userDiveceToken'] 	= (@$val->user->deviceToken) ? @$val->user->deviceToken : '';
					// $Darray[$i]['userEmail'] 		= (@$val->user->email) ? @$val->user->email : '';
					// $Darray[$i]['usercontactNumber'] = (@$val->user->contactNumber) ? @$val->user->contactNumber : '';
					
					$Darray[$i]['created'] 				= ($val->created) ? date("F jS, Y, g:i a", strtotime($val->created)) : '';
					$i++;
				}
				$response['status'] = "success";
				//$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
				$response['order_data'] = (@$order_Details) ?  @$order_Details : array();
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'Order number not available.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId  and orderId are required.';
		}
		return  $response;
		
	}

	
	
		function orderdelivered(){
			$this->loadModel('Orderes');$this->loadModel('Carts');$this->loadModel('Users');
			if($this->request->getData['orderId'] !='' && $this->request->getData['vendorId'] !='' && $this->request->getData['status'] !=''){
				$orders = $this->Orderes->find()->where(['Orderes.id'=>$this->request->getData['orderId']])->contain(['Carts'])->first();
				//pr($orders); die;
				
				if(@$orders->id !=''){
					$shippingCode = (@$this->request->getData['shippingCode']) ? @$this->request->getData['shippingCode'] : '0';
					$articles = TableRegistry::get('Orderes');
					$query = $articles->query();
					$query->update()
						->set(['shippingDate'=>date('Y-m-d H:i:s'),'shippingCode'=>$shippingCode, 'orderStatus' => $this->request->getData['status']])
						->where(['id' => $orders->id])
						->execute();
					
					if($this->request->getData['status'] == 3){
						$user_d = $this->Users->find()->where(['Users.id'=>$this->request->getData['vendorId']])->first();
						$articles = TableRegistry::get('Users');
						$query = $articles->query();
						$query->update()
							->set(['wallet' => ($user_d->wallet + $orders->TotalAmount)])
							->where(['id' => $user_d->id])
							->execute();
					}
					$response['status'] = "success";
					$response['msg'] = 'Order status changed successfully.';
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'Order no longer available.';
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'userId and orderId are required.';
			}
			return  $response;
		}


		function deliveryconfirm(){
			$this->loadModel('Orderes');$this->loadModel('Carts');$this->loadModel('Users');
			if($this->request->getData['orderId'] !='' && $this->request->getData['userId'] !='' && $this->request->getData['status'] !=''){
				$orders = $this->Orderes->find()->where(['Orderes.id'=>$this->request->getData['orderId']])->contain(['Carts'])->first();
				//pr($orders); die;
				
				if(@$orders->id !=''){
					$articles = TableRegistry::get('Orderes');
					$query = $articles->query();
					$query->update()
						->set(['deliveryConfirm'=>$this->request->getData['status']])
						->where(['id' => $orders->id])
						->execute();
					
					
					$response['status'] = "success";
					$response['msg'] = 'Delivery confirmed successfully.';
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'Order no longer available.';
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'userId and orderId are required.';
			}
			return  $response;
		}
		
		function addtocart(){
			$this->loadModel('Carts');
			if($this->request->getData['userId'] !='' && $this->request->getData['productId'] !='' && $this->request->getData['quantity'] !=''){
			$carts = $this->Carts->newEntity();
				$carts = $this->Carts->patchEntity($carts, $this->request->getData, ['validate' => false]);
				$carts['status'] = 1;
				$carts['created'] = date('Y-m-d H:i');
				if ($this->Carts->save($carts)) {
					$response['status'] = 'success';
					$response['msg'] = 'Data save successfully.';
					$DD['cartId'] = $carts->id;
					$response['data'] = $DD;
				}else{
					$response['status'] = 'Fails';
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'userId, categoryId, productId, quantity and price are required';
			}
			return  $response;
		}
				
		function editcart(){
			$this->loadModel('Carts');
			if($this->request->getData['userId'] !='' && $this->request->getData['cartId'] !=''){
				$carts = $this->Carts->find()->where(['Carts.userId'=>$this->request->getData['userId'], 'Carts.id'=>$this->request->getData['cartId']])->first();
				if(@$carts->id !='') {
					$carts = $this->Carts->patchEntity($carts, $this->request->getData, ['validate' => false]);
					$carts['modified'] = date('Y-m-d H:i');
					$this->Carts->save($carts);
					$response['status'] = 'success';
					$response['msg'] = 'Data save successfully.';
					$DD = $this->cartlist($this->request->getData['userId']);
					$response['data'] = $DD['data'];
				}else{
					$response['status'] = 'Fails';
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'userId, and cartId are required';
			}
			return  $response;
		}
			
		function cartlist($userId=NULL){
			$this->loadModel('Carts');
			$VendorID = ($userId)? $userId : $this->request->getData['userId'];
			$userservices = $this->Carts->find('all')->where(['Carts.orderId is'=>null,'Carts.userId'=>$VendorID])->contain(['Products'])->toArray();
			
			$Darray = array();
			$i=0;
			foreach($userservices as $val){
				$Darray[$i]['cartId'] 				= ($val->id) ? $val->id : '';
				$Darray[$i]['categoryId'] 			= ($val->categoryId) ? $val->categoryId : '';
				$Darray[$i]['subCategoryId'] 		= ($val->subCategoryId) ? $val->subCategoryId : '';
				$Darray[$i]['productId'] 			= ($val->productId) ? $val->productId : '';
				$Darray[$i]['productOwner'] 		= ($val->productOwner) ? $val->productOwner : '';
				$Darray[$i]['SKU'] 					= (@$val->product->SKU) ? @$val->product->SKU : '';
				$Darray[$i]['productName'] 			= (@$val->product->productName) ? @$val->product->productName : '';
				$Darray[$i]['product_description'] 	= (@$val->product->description) ? @$val->product->description : '';
				$Darray[$i]['quantity'] 			= ($val->quantity) ? $val->quantity : '';
				$Darray[$i]['TotalQuantity'] 		= (@$val->product->quantity) ? @$val->product->quantity : 0;
				$Darray[$i]['specialPrice'] 		= (@$val->product->specialPrice) ? @$val->product->specialPrice : 0;
				$Darray[$i]['unitPrice'] 			= (@$val->product->unitPrice) ? @$val->product->unitPrice : 0;
				$Darray[$i]['shippingAmount'] 		= (@$val->product->shippingAmount) ? @$val->product->shippingAmount : 0;
				$Darray[$i]['TotalPrice'] 			= ($val->TotalPrice) ? $val->TotalPrice : 0;
				$Darray[$i]['image'] 				= (@$val->product->image) ? $this->productPath.@$val->product->image : '';
				$Darray[$i]['created'] 				= ($val->created) ? date("F jS, Y, g:i a", strtotime($val->created)) : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
			return  $response;
		}
			
		private function deletecart(){
			$this->loadModel('Carts');
			$this->loadModel('Freestaffs');
			$this->loadModel('Freestafflikes');
			
			if($this->request->getData('cartId') !='' && $this->request->getData('userId') !=''){
				$listing = $this->Carts->find()->where(['Carts.id'=>$this->request->getData('cartId'), 'Carts.userId'=>$this->request->getData('userId')])->first();
				if($listing) {
					$this->Carts->delete($listing);
					$response['status'] = 'success';
					$response['msg'] = 'Item deleted succesfully.';
					$dd = $this->cartlist($this->request->getData('userId'));
					$response['data'] = $dd['data'];
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'cartId does not exist';
				}
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'cartId is required';
			}
			return  $response;
		}	
				
			
			
			
			private function addstaff(){
					$this->loadModel('Freestaffs');
					$this->loadModel('Products');
					if($this->request->getData['postTitle'] !='' && $this->request->getData['userId'] !='' && $this->request->getData['categoryId'] !='' && $this->request->getData['description'] !='' && $this->request->getData['image_1'] !=''){
							//@$PreV = $this->Products->find()->where(['Products.SKU'=>$this->request->getData['SKU']])->first();
							
									$products = $this->Freestaffs->newEntity();
									$products = $this->Freestaffs->patchEntity($products, $this->request->getData, ['validate' => false]);
									$products['status'] = 1;
									$products['created'] = date('Y-m-d H:i');
									$fileName1 = '';
									$fileName2 = '';
									$fileName3 = '';
									$fileName4 = '';
									$fileName5 = '';
									$videoName = '';
									
									if(@$_FILES['video']['name'] !=''){
										$videoName=time().stripslashes($_FILES['video']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$videoName = str_replace($str,$rplc,$videoName);
										$products['video']=$videoName;
									}
									if(@$_FILES['image_1']['name'] !=''){
										$fileName1=time().stripslashes($_FILES['image_1']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName1 = str_replace($str,$rplc,$fileName1);
										$products['image_1']=$fileName1;
									}
									if(@$_FILES['image_2']['name'] !=''){
										$fileName2=time().stripslashes($_FILES['image_2']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName2 = str_replace($str,$rplc,$fileName2);
										$products['image_2']=$fileName2;
									}
									if(@$_FILES['image_3']['name'] !=''){
										$fileName3=time().stripslashes($_FILES['image_3']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName3 = str_replace($str,$rplc,$fileName3);
										$products['image_3']=$fileName3;
									}
									if(@$_FILES['image_4']['name'] !=''){
										$fileName4=time().stripslashes($_FILES['image_4']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName4 = str_replace($str,$rplc,$fileName4);
										$products['image_4']=$fileName4;
									}
									if(@$_FILES['image_5']['name'] !=''){
										$fileName5=time().stripslashes($_FILES['image_5']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName5 = str_replace($str,$rplc,$fileName5);
										$products['image_5']=$fileName5;
									}
									
									//$path = WWW_ROOT . 'img/uploads/feature/original/';
									if ($this->Freestaffs->save($products)) {
										if(@$_FILES['video']['name'] !='' && $videoName !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$videoName;
													$source=$_FILES['video']['tmp_name'];
													move_uploaded_file($source,$target);
											}
											if(@$_FILES['image_1']['name'] !='' && $fileName1 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName1;
													$source=$_FILES['image_1']['tmp_name'];
													move_uploaded_file($source,$target);
											}
											if(@$_FILES['image_2']['name'] !='' && $fileName2 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName2;
													$source=$_FILES['image_2']['tmp_name'];
													move_uploaded_file($source,$target);
											}
											if(@$_FILES['image_3']['name'] !='' && $fileName3 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName3;
													$source=$_FILES['image_3']['tmp_name'];
													move_uploaded_file($source,$target);
											}
											if(@$_FILES['image_4']['name'] !='' && $fileName4 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName4;
													$source=$_FILES['image_4']['tmp_name'];
													move_uploaded_file($source,$target);
											}
											if(@$_FILES['image_5']['name'] !='' && $fileName5 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName5;
													$source=$_FILES['image_5']['tmp_name'];
													move_uploaded_file($source,$target);
											}
											
												$response['status'] = 'success';
												$response['msg'] = 'Data save successfully.';
												
												$res = $this->stufflist($this->request->getData['userId']);
												$response['data'] = $res['data'];
									}
							
					}else{
						$response['status'] = 'Fails';
						$response['msg'] = 'categoryId, userId, postTitle, description and image_1 are required';
					}
					return  $response;
			}
			
			private function editstaff(){
					$this->loadModel('Freestaffs');
					$this->loadModel('Products');
					if($this->request->getData['staffId'] !='' && $this->request->getData['userId'] !=''){
							$products = $this->Freestaffs->find()->where(['Freestaffs.id'=>$this->request->getData['staffId']])->first();
							if(@$products){
									$products = $this->Freestaffs->patchEntity($products, $this->request->getData, ['validate' => false]);
									$products['status'] = 1;
									$products['created'] = date('Y-m-d H:i');
									$fileName1 = '';$OLDfileName1 = '';
									$fileName2 = '';$OLDfileName2 = '';
									$fileName3 = '';$OLDfileName3 = '';
									$fileName4 = '';$OLDfileName4 = '';
									$fileName5 = '';$OLDfileName5 = '';
									$videoName = '';$OLDvideoName = '';
									
									if(@$_FILES['video']['name'] !=''){
										$videoName=time().stripslashes($_FILES['video']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$videoName = str_replace($str,$rplc,$videoName);
											$products['video']=$videoName;
											$OLDvideoName = $products->video;
									}
									if(@$_FILES['image_1']['name'] !=''){
										$fileName1=time().stripslashes($_FILES['image_1']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName1 = str_replace($str,$rplc,$fileName1);
										$products['image_1']=$fileName1;
										$OLDfileName1 = $products->image_1;
									}
									if(@$_FILES['image_2']['name'] !=''){
										$fileName2=time().stripslashes($_FILES['image_2']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName2 = str_replace($str,$rplc,$fileName2);
										$products['image_2']=$fileName2;
										$OLDfileName2 = $products->image_2;
									}
									if(@$_FILES['image_3']['name'] !=''){
										$fileName3=time().stripslashes($_FILES['image_3']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName3 = str_replace($str,$rplc,$fileName3);
										$products['image_3']=$fileName3;
										$OLDfileName3 = $products->image_3;
									}
									if(@$_FILES['image_4']['name'] !=''){
										$fileName4=time().stripslashes($_FILES['image_4']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName4 = str_replace($str,$rplc,$fileName4);
										$products['image_4']=$fileName4;
										$OLDfileName4 = $products->image_4;
									}
									if(@$_FILES['image_5']['name'] !=''){
										$fileName5=time().stripslashes($_FILES['image_5']['name']);
											$str = [' ',"'",'"',"(", ")", "[", "]", "!","@","#","$","%","^","&","*"];
											$rplc =['','',"","","","","","","","","","","","",""];
											$fileName5 = str_replace($str,$rplc,$fileName5);
										$products['image_5']=$fileName5;
										$OLDfileName5 = $products->image_5;
									}
									
									//$path = WWW_ROOT . 'img/uploads/feature/original/';
									if ($this->Freestaffs->save($products)) {
											if(@$_FILES['video']['name'] !='' && $videoName !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$videoName;
													$source=$_FILES['video']['tmp_name'];
													move_uploaded_file($source,$target);
													$urLV = $target.$OLDvideoName;
													@unlink($urLV);
											}
											if(@$_FILES['image_1']['name'] !='' && $fileName1 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName1;
													$source=$_FILES['image_1']['tmp_name'];
													move_uploaded_file($source,$target);
													$urL1 = $target.$OLDfileName1;
													@unlink($urL1);
											}
											if(@$_FILES['image_2']['name'] !='' && $fileName2 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName2;
													$source=$_FILES['image_2']['tmp_name'];
													move_uploaded_file($source,$target);
													$urL2 = $target.$OLDfileName2;
													@unlink($urL2);
											}
											if(@$_FILES['image_3']['name'] !='' && $fileName3 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName3;
													$source=$_FILES['image_3']['tmp_name'];
													move_uploaded_file($source,$target);
													$urL3 = $target.$OLDfileName3;
													@unlink($urL3);
											}
											if(@$_FILES['image_4']['name'] !='' && $fileName4 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName4;
													$source=$_FILES['image_4']['tmp_name'];
													move_uploaded_file($source,$target);
													$urL4 = $target.$OLDfileName4;
													@unlink($urL4);
											}
											if(@$_FILES['image_5']['name'] !='' && $fileName5 !=''){
													$target = WWW_ROOT.'/img/uploads/freestaff/'.$fileName5;
													$source=$_FILES['image_5']['tmp_name'];
													move_uploaded_file($source,$target);
													$urL5 = $target.$OLDfileName5;
													@unlink($urL5);
											}
											
												$response['status'] = 'success';
												$response['msg'] = 'Data save successfully.';
												
												$res = $this->stufflist($this->request->getData['userId']);
												$response['data'] = $res['data'];
									}
							}else{
								$response['status'] = 'Fails';
								$response['msg'] = 'stuff no longer available.';
							}
							
					}else{
						$response['status'] = 'Fails';
						$response['msg'] = 'staffId and userId are required';
					}
					return  $response;
			}
	
			
			private function stufflist($userId=NULL){
					$this->loadModel('Freestaffs');
					$this->loadModel('User');
					@$USERID = ($userId) ? $userId : $this->request->getData('userId');
					@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
						
								$this->paginate = [
										'limit' => '10',//$limit,
										'order' => [
												'Freestaffs.id' => 'desc'
											],
										'page'=>$pageNo,
										'contain' => ['Categories', 'Users']
								];
								$keyword = $this->request->query('keyword');
								$keyword = $this->request->query('category');
								if($USERID !=''){
									$condition[] = ['Freestaffs.userId'=>$USERID];
								}else{
									$condition[] = ['Freestaffs.status'=>1];
								}
								if($keyword !=''){
									$condition[] = ['OR'=>[
														'Freestaffs.postTitle LIKE'=>'%'.$keyword.'%',
														'Freestaffs.description LIKE'=>'%'.$keyword.'%',
														'Categories.name LIKE'=>'%'.$keyword.'%'
													]
												];
								}
								$userservices =array();    
								$query = $this->Freestaffs->find('all')->where($condition);
								$totalCount =  $query->count();
								if($pageNo <= ceil($totalCount/10)){
									$userservices = $this->paginate($query);
									$userservices = $userservices->toArray();
								}
								$Darray = array();
								$i=0;
								//echo "<pre>"; print_r($userservices); die;
								foreach($userservices as $val){
									$Darray[$i]['staffId'] 			= ($val->id) ? $val->id : '';
									$Darray[$i]['userId'] 			= ($val->userId) ? $val->userId : '';
									$Darray[$i]['categoryId'] 		= ($val->categoryId) ? $val->categoryId : '';
									$Darray[$i]['categoryName'] 	= (@$val->category->name) ? @$val->category->name : '';
									
									$Darray[$i]['userName'] 		= (@$val->user->fullName) ? @$val->user->fullName : '';
									$Darray[$i]['userImage'] 		= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : '';
					
									$Darray[$i]['postTitle'] 		= ($val->postTitle) ? $val->postTitle : '';
									$Darray[$i]['description'] 		= ($val->description) ? $val->description : '';
									
									$Darray[$i]['image_1'] 			= ($val->image_1) ? $this->staffPath.$val->image_1 : '';
									$Darray[$i]['image_2'] 			= ($val->image_2) ? $this->staffPath.$val->image_2 : '';
									$Darray[$i]['image_3'] 			= ($val->image_3) ? $this->staffPath.$val->image_3 : '';
									$Darray[$i]['image_4'] 			= ($val->image_4) ? $this->staffPath.$val->image_4 : '';
									$Darray[$i]['image_5'] 			= ($val->image_5) ? $this->staffPath.$val->image_5 : '';
									$Darray[$i]['video'] 			= ($val->video) ? $this->staffPath.$val->video : '';
									$Darray[$i]['videolink'] 		= ($val->videolink) ? $val->videolink : '';
								
									$Darray[$i]['totalComment'] 	= ($val->totalComment) ? $val->totalComment : '0';
									$Darray[$i]['created'] 			= ($val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
									
									//$Darray[$i]['modified'] 	= ($val->listing->EXP) ? $val->listing->EXP : '';
									$Darray[$i]['timezone'] = $this->timezone();
									$Darray[$i]['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
									$Darray[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
										
									$i++;
								}
								$response['status'] = "success";
								$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
						
					return  $response;
			}
			
			private function stuffdetail($staffId=NULL){
				$this->loadModel('Freestaffs');
				$this->loadModel('Freestaffcomments');
				$this->loadModel('Freestafflikes');
				$this->loadModel('User');
				@$USERID = ($staffId) ? $staffId : $this->request->getData('staffId');
				@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
						
				$val = $this->Freestaffs->find()->where(['Freestaffs.id'=>$USERID])->contain(['Categories','Users'])->first();
				
				$Darray = array();
				$i=0;
				//echo "<pre>"; print_r($userservices); die;
			
					$Darray['staffId'] 				= ($val->id) ? $val->id : '';
					$Darray['userId'] 				= ($val->userId) ? $val->userId : '';
					$Darray['categoryId'] 			= ($val->categoryId) ? $val->categoryId : '';
					$Darray['categoryName'] 		= (@$val->category->name) ? @$val->category->name : '';
					
					$Darray['userName'] 			= (@$val->user->fullName) ? @$val->user->fullName : '';
					$Darray['userImage'] 			= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : '';
	
					$Darray['postTitle'] 			= ($val->postTitle) ? $val->postTitle : '';
					$Darray['description'] 			= ($val->description) ? $val->description : '';
					
					$Darray['image_1'] 				= ($val->image_1) ? $this->staffPath.$val->image_1 : '';
					$Darray['image_2'] 				= ($val->image_2) ? $this->staffPath.$val->image_2 : '';
					$Darray['image_3'] 				= ($val->image_3) ? $this->staffPath.$val->image_3 : '';
					$Darray['image_4'] 				= ($val->image_4) ? $this->staffPath.$val->image_4 : '';
					$Darray['image_5'] 				= ($val->image_5) ? $this->staffPath.$val->image_5 : '';
					$Darray['video'] 				= ($val->video) ? $this->staffPath.$val->video : '';
					$Darray['videolink'] 			= ($val->videolink) ? $val->videolink : '';
				
					$Darray['TotalLike'] 			= $this->Freestafflikes->find('all')->where(['Freestafflikes.status'=>1,'Freestafflikes.freestaffId'=>$val->id])->count();
					$Darray['TotalDislike'] 		= $this->Freestafflikes->find('all')->where(['Freestafflikes.status'=>2,'Freestafflikes.freestaffId'=>$val->id])->count();
					$Darray['totalComment'] 		= ($val->totalComment) ? $val->totalComment : '0';
					$Darray['totalView'] 			= ($val->totalView) ? $val->totalView : '0';
					
					if(@$this->request->getData['userId'] !=''){
						@$likes = $this->Freestafflikes->find()->where(['Freestafflikes.freestaffId'=>$val->id,'Freestafflikes.userId'=>$this->request->getData['userId']])->first();
					}
					$Darray['likeStatus'] 			= (@$likes->id !='') ? $likes->status : '0';
					
					$Darray['timezone'] = $this->timezone();
					$Darray['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
					$Darray['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
										

					//$Darray['created'] 									= ($val->listing->businessAddress) ? $val->listing->businessAddress : '';
				
				//$COMMENT = $this->commentlist($val->id, '1');
				//$Darray['commentList'] =
				$Darray['created'] 									= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
				$response['status'] = "success";
				$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
						
				return  $response;
			}
			
			function stuffview($staffId=NULL){
					$this->loadModel('Freestaffs');
					@$USERID = ($staffId) ? $staffId : $this->request->getData('staffId');
					if($USERID !=''){
							$val = $this->Freestaffs->find()->where(['Freestaffs.id'=>$USERID])->first();
							$TOTAL = ($val->totalView) + 1;
							$articles = TableRegistry::get('Freestaffs');
							$query = $articles->query();
							$query->update()
								->set(['totalView' => $TOTAL])
								->where(['id' => $this->request->getData['staffId']])
								->execute();
					}
				$response['status'] = "success";
					return  $response;
			}
			
	private function deletestuff(){
		$this->loadModel('Freestaffcomments');
		$this->loadModel('Freestaffs');
		$this->loadModel('Freestafflikes');
		
		if($this->request->getData('freestaffId') !='' && $this->request->getData('userId') !=''){
			$listing = $this->Freestaffs->find()->where(['Freestaffs.id'=>$this->request->getData('freestaffId'), 'Freestaffs.userId'=>$this->request->getData('userId')])->first();
			if($listing) {
				$PATH = WWW_ROOT.'/img/uploads/freestaff/';
					@unlink($PATH.$listing->image_1);
					@unlink($PATH.$listing->image_2);
					@unlink($PATH.$listing->image_3);
					@unlink($PATH.$listing->image_4);
					@unlink($PATH.$listing->image_5);
					@unlink($PATH.$listing->video);
					$this->Freestaffcomments->deleteAll(['Freestaffcomments.freestaffId'=>$listing->id]);
					$this->Freestafflikes->deleteAll(['Freestafflikes.freestaffId'=>$listing->id]);
					$this->Freestaffs->delete($listing);
					$response['status'] = 'success';
					$response['msg'] = 'Free Stuff deleted succesfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'FreeStuff no longer available';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'freestaffId is required';
		}
		return  $response;
	}

	private function deletestuffimage(){
		$this->loadModel('Freestaffcomments');
		$this->loadModel('Freestaffs');
		$this->loadModel('Freestafflikes');
		
		if($this->request->getData('freestaffId') !='' && $this->request->getData('userId') !='' && $this->request->getData['image_key'] !=''){
			$listing = $this->Freestaffs->find()->where(['Freestaffs.id'=>$this->request->getData('freestaffId'), 'Freestaffs.userId'=>$this->request->getData('userId')])->first();
			if($listing) {
				$dd = $this->request->getData['image_key'];
				$IMG_U = $listing->$dd;
				$PATH = WWW_ROOT.'/img/uploads/freestaff/';
					@unlink($PATH.$IMG_U);
					$articles = TableRegistry::get('Freestaffs');
					$query = $articles->query();
					$query->update()
						->set([$dd => ''])
						->where(['id' => $this->request->getData['freestaffId']])
						->execute();
					$response['status'] = 'success';
					$response['msg'] = 'Free Stuff image deleted succesfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'FreeStuff no longer available';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'freestaffId is required';
		}
		return  $response;
	}

			function stufflikes(){
				if($this->request->getData['status'] !='' && $this->request->getData['userId'] !=''){
					$this->loadModel('Freestafflikes');
					$articleId = (@$this->request->getData['freestaffId']) ? @$this->request->getData['freestaffId'] : '';
					if($articleId !=''){
								$likes = $this->Freestafflikes->find()->where(['Freestafflikes.freestaffId'=>$articleId,'Freestafflikes.userId'=>$this->request->getData['userId']])->first();
							if($this->request->getData['status'] == 0){
								if(@$likes->id !=''){
									$this->Freestafflikes->delete($likes);
								}
							}else{
									if(@$likes->id ==''){
										$likes = $this->Freestafflikes->newEntity();
									}
										@$this->request->getData['status'] = ($this->request->getData['status']) ? $this->request->getData['status'] : 0;
										$likes = $this->Freestafflikes->patchEntity($likes, $this->request->getData, ['validate' => false]);
										$likes['created'] = date('Y-m-d H:i');
										$this->Freestafflikes->save($likes);
							}
								$response['status'] = 'Success'; 
								$response['msg'] = "Like submited successfully.";
					}else{
								$response['status'] = 'Fail'; 
								$response['msg'] = 'Stuff Id and status are required.';
					}
				}else{
					$response['status'] = 'Fail'; 
					$response['msg'] = 'status and userId are required.';
				}
				return $response;
			}
		
		
		function addcomment(){
			$this->loadModel('Freestaffcomments');
			$this->loadModel('Freestaffs');
				if($this->request->getData['userId'] !='' && $this->request->getData['freestaffId'] !='' && $this->request->getData['comment'] !=''){
					if(@$this->request->getData['commentId'] !=''){
						$comments = $this->Freestaffcomments->find()->where(['Freestaffcomments.id'=>$this->request->getData['commentId'],'Freestaffcomments.userId'=>$this->request->getData['userId']])->first();
						if(@$comments->id ==''){
							$comments = $this->Freestaffcomments->newEntity();
						}
					}else{
						$comments = $this->Freestaffcomments->newEntity();
					}
					$comments = $this->Freestaffcomments->patchEntity($comments, $this->request->getData, ['validate' => false]);
					$comments['status'] 	= 1;
					$comments['created'] 	= date('Y-m-d H:i:s');
					$comments['added_time'] = date('Y-m-d H:i:s',strtotime($this->request->getData['added_time']));
					if($this->Freestaffcomments->save($comments)){
						$listingD = $this->Freestaffs->find()->where(['Freestaffs.id' => $this->request->getData['freestaffId']])->first();
						$TOTAL = @$listingD->totalComment+1;
						$articles = TableRegistry::get('Freestaffs');
						$query = $articles->query();
						$query->update()
							->set(['totalComment' => $TOTAL])
							->where(['id' => $this->request->getData['freestaffId']])
							->execute();
							$response['status'] = "success";
							$response['msg'] = 'Comment added successfully.';
					}
				}else{
						$response['status'] = 'Fails';
						$response['msg'] = 'UserId is required.';
				}
				return  $response;
		}

				function commentdelete(){
					$this->loadModel('Freestaffcomments');
					$this->loadModel('Freestaffs');
					if($this->request->getData['userId'] !='' && $this->request->getData['freestaffId'] !='' && $this->request->getData['commentId'] !=''){
						$comments = $this->Freestaffcomments->find()->where(['Freestaffcomments.id'=>$this->request->getData['commentId'],'Freestaffcomments.freestaffId'=>$this->request->getData['freestaffId'],'Freestaffcomments.userId'=>$this->request->getData['userId']])->first();
							if(@$comments->id !=''){
								$this->Freestaffcomments->delete($comments);
								$listingD = $this->Freestaffs->find()->where(['Freestaffs.id' => $this->request->getData['freestaffId']])->first();
								$TOTAL = @$listingD->totalComment-1;
								$articles = TableRegistry::get('Freestaffs');
								$query = $articles->query();
								$query->update()
									->set(['totalComment' => $TOTAL])
									->where(['id' => $this->request->getData['freestaffId']])
									->execute();
									$response['status'] = "success";
									$response['msg'] = 'Comment deleted successfully.';
							}
						}else{
								$response['status'] = 'Fails';
								$response['msg'] = 'UserId is required.';
						}
						return  $response;
				}
				
				
				function commentlist($freestaffId=NULL,$PAGENO=NULL){
								$this->loadModel('Freestaffcomments');
								@$pageNo = ($PAGENO) ? $PAGENO : ($this->request->getData('pageNo') ? $this->request->getData('pageNo') : '1');
								@$freestaffID = ($freestaffId) ? $freestaffId : $this->request->getData['freestaffId'];
								if($pageNo !=''){
										$this->paginate = [
											'limit' => '10',//$limit,
											'order' => [
													'Freestaffcomments.id' => 'desc'
													],
											'page'=>$pageNo,
											'contain' => ['Users']
											];
										$keyword = $this->request->query('keyword');
										$condition[] = ['Freestaffcomments.freestaffId'=>$this->request->getData['freestaffId'],'Freestaffcomments.status'=>1];
										     
									$query = $this->Freestaffcomments->find('all')->where($condition);
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
										$Darray[$i]['freestaffcommentId'] 		= (@$val->id) ? @$val->id : '';
										$Darray[$i]['comment'] 					= (@$val->comment) ? @$val->comment : '';
										$Darray[$i]['created'] 					= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
										$Darray[$i]['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
										$Darray[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
										
										$Darray[$i]['UserfullName'] 			= (@$val->user->fullName) ? @$val->user->fullName : '';
										$Darray[$i]['Useremail'] 				= (@$val->user->email) ? @$val->user->email : '';
										$Darray[$i]['Userprofile_picture'] 		= (@$val->user->profile_picture) ? $this->profilePath.@$val->user->profile_picture : '';
										$Darray[$i]['userId'] 					= (@$val->user->id) ? @$val->user->id : '';
										
										$Darray[$i]['timezone'] = $this->timezone();
										$i++;
									}
									$response['status'] = "success";
									$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
							}else{
									$response['status'] = 'Fails';
									$response['msg'] = 'UserId is required.';
							}
							return  $response;
							
				}
			
			
				function searchnew(){
						$this->loadModel('Users');
						$this->loadModel('Specializations');
						if(isset($this->request->getData['typeOfBusiness'])){
							$typeOfBusiness = $this->request->getData['typeOfBusiness'];
							$keyword = $this->request->getData['keyword'];
							//$subCategoryId = $this->request->getData['categoryId'];
							$this->paginate = [
								'limit' => '50',//$limit,
								//'order' => ['Users.id' => 'desc'],
								'order' => ['rand()'],
								//'contain' => ['ReportedProfiles']
								];
							$keyword = $this->request->query('keyword');
							$condition[] = ['Users.status' => 1];
							if($categoryId != ''){
								$condition[] = ['FIND_IN_SET(\''. $typeOfBusiness .'\',Users.typeOfBusiness)'];
							}
							if($keyword != ''){
								$condition[] = [
									'OR'=>[
											'Users.fullName LIKE' => '%'.$keyword.'%',
											'Users.email LIKE' => '%'.$keyword.'%',
											'Users.contactNumber LIKE' => '%'.$keyword.'%',
											'Users.VFirstName LIKE' => '%'.$keyword.'%',
											'Users.VBusinessName LIKE' => '%'.$keyword.'%',
											'Users.VBusinessAddress LIKE' => '%'.$keyword.'%',
											'Users.VBSuite LIKE' => '%'.$keyword.'%',
											'Users.Vcity LIKE' => '%'.$keyword.'%',
											'Users.VAstate LIKE' => '%'.$keyword.'%',
											'Users.VZipcode LIKE' => '%'.$keyword.'%',
											'Users.VPhone LIKE' => '%'.$keyword.'%',
											'Users.VEmail LIKE' => '%'.$keyword.'%',
											'Users.VState LIKE' => '%'.$keyword.'%',
											]
										];
							}
							$query = $this->Users->find('all')->where($condition);
							$products = $this->paginate($query);
							$products = $products->toArray();
							$Darray = array();
							$i=0;
							foreach($products as $val){
								$Darray[$i]['userId'] 				= ($val->id) ? $val->id : '';
								$Darray[$i]['fullName'] 			= ($val->fullName) ? $val->fullName : '';
								$Darray[$i]['contactNumber'] 		= ($val->contactNumber) ? $val->contactNumber : '';
								
								$Darray[$i]['VFirstName'] 			= ($val->VFirstName) ? $val->VFirstName : '';
								$Darray[$i]['VBusinessName'] 		= ($val->VBusinessName) ? $val->VBusinessName : '';
								$Darray[$i]['VBusinessAddress']		= ($val->VBusinessAddress) ? $val->VBusinessAddress : '';
								
								$Darray[$i]['VBSuite'] 								= ($val->VBSuite) ? $val->VBSuite : '';
								$Darray[$i]['Vcity'] 										= ($val->Vcity) ? $val->Vcity : '';
								$Darray[$i]['VAstate'] 								= ($val->VAstate) ? $val->VAstate : '';
								
								$Darray[$i]['VState'] 									= ($val->VState) ? $val->VState : '';
								$Darray[$i]['VZipcode'] 							= ($val->VZipcode) ? $val->VZipcode : '';
								$Darray[$i]['VPhone'] 									= ($val->VPhone) ? $val->VPhone : '';
								
								$Darray[$i]['VEmail'] 									= ($val->VEmail) ? $val->VEmail : '';
								$Darray[$i]['mobileClicnic'] 		= ($val->mobileClicnic) ? $val->mobileClicnic : '';
								$Darray[$i]['image'] 										= ($val->profile_picture) ? $this->profilePath.$val->profile_picture : '';
								$articleArray = array();
							if($val->Specialization !=''){
									$ss = explode(",",$val->Specialization);
									$atriclesD = $this->Specializations->find('all')->where(['Specializations.id IN'=>$ss])->toArray();
										$D=0;
										foreach($atriclesD as $val2){
											$articleArray[$D]['id'] 			= $val2->id;
											$articleArray[$D]['name'] 	= $val2->name;
											$D++;
										}
								}
								$Darray[$i]['specializations'] = $articleArray;
								$i++;
							}
							$response['status'] = "success";
							$response['response'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
						}else{
							$response['status'] = 'Fails';
							$response['msg'] = 'typeOfBusiness is required.';
						}
						return  $response;
						
				}
				
				
		function searchdetail($userId = NULL){
			$this->loadModel('Users');
			$this->loadModel('Reviews');
			$this->loadModel('Specializations');
			$userID = ($userId) ? $userId : $this->request->getData('userId');
			$latitude = '';
			$longitude = '';
			if(trim(@$this->request->getData['lat']) !=''){
				$latitude = $this->request->getData['lat'];
			}
			if(trim(@$this->request->getData['longs']) !=''){
				$longitude = $this->request->getData['longs'];
			}
			//$userD = $this->Users->find()->where(['Users.id' => $userID])->contain(['Countries','States'])->first();
			$userD = $this->Users->find()->where(['Users.id' => $userID])->first();
			//pr($userD); die;
			if($userD){
				$UserDetail['userId'] 			= ($userD->id) ? $userD->id : '';
				$UserDetail['fullName'] 		= ($userD->fullName) ? $userD->fullName : '';
				$UserDetail['lastName'] 		= ($userD->lastName) ? $userD->lastName : '';
				$UserDetail['email'] 			= ($userD->email) ? $userD->email : '';
				$UserDetail['role'] 			= ($userD->role) ? $userD->role : '';
				$UserDetail['address'] 			= ($userD->address) ? $userD->address : '';
				$UserDetail['city'] 			= ($userD->city) ? $userD->city : '';
				$UserDetail['zipCode'] 			= ($userD->zipCode) ? $userD->zipCode : '';
				$UserDetail['contactNumber'] 	= ($userD->contactNumber) ? $userD->contactNumber :'';
				$UserDetail['image'] 			= ($userD->profile_picture) ? $this->profilePath.$userD->profile_picture : '';
				if(strpos($userD->profile_picture, "http") !== false){
					$UserDetail['image'] = $userD->profile_picture;
				}
				$UserDetail['BImage'] 			= ($userD->profile_picture) ? $this->profilePath.$userD->BImage : '';
				
				$UserDetail['VFirstName'] 		= ($userD->VFirstName) ? $userD->VFirstName : '';
				$UserDetail['VLastName'] 		= ($userD->VLastName) ? $userD->VLastName : '';
				$UserDetail['VLicenseNo'] 		= ($userD->VLicenseNo) ? $userD->VLicenseNo : '';
				$UserDetail['VState'] 			= ($userD->VState) ? $userD->VState : '';
				$UserDetail['VBusinessName'] 	= ($userD->VBusinessName) ? $userD->VBusinessName : '';
				$UserDetail['VTaxID'] 			= ($userD->VTaxID) ? $userD->VTaxID : '';
				$UserDetail['VBusinessAddress'] = ($userD->VBusinessAddress) ? $userD->VBusinessAddress : '';
				$UserDetail['VBSuite'] 			= ($userD->VBSuite) ? $userD->VBSuite : '';
				
				$UserDetail['Vcity'] 			= ($userD->Vcity) ? $userD->Vcity : '';
				$UserDetail['VAstate'] 			= ($userD->VAstate) ? $userD->VAstate : '';
				$UserDetail['VZipcode'] 		= ($userD->VZipcode) ? $userD->VZipcode : '';
				$UserDetail['VPhone'] 			= ($userD->VPhone) ? $userD->VPhone : '';
				$UserDetail['VEmail'] 			= ($userD->VEmail) ? $userD->VEmail : '';
				$UserDetail['mobileClicnic']	= ($userD->mobileClicnic) ? $userD->mobileClicnic : '';
				$UserDetail['FixedTraditional']	= ($userD->FixedTraditional) ? $userD->FixedTraditional : '';
				$UserDetail['Freelance'] 		= ($userD->Freelance) ? $userD->Freelance : '';
				$UserDetail['YearInBusiness']	= ($userD->YearInBusiness) ? $userD->YearInBusiness : '';
				$UserDetail['typeOfPets'] 		= ($userD->typeOfPets) ? $userD->typeOfPets : '';
				
				$UserDetail['TypeOfService'] 	= ($userD->TypeOfService) ? $userD->TypeOfService : '';
				$UserDetail['Specialization']	= ($userD->Specialization) ? $userD->Specialization : '';
				$UserDetail['digonasticLab'] 	= ($userD->digonasticLab) ? $userD->digonasticLab : '';
				$UserDetail['Imaging'] 			= ($userD->Imaging) ? $userD->Imaging : '';
				$UserDetail['other'] 			= ($userD->other) ? $userD->other : '';
				$UserDetail['categoryId'] 		= ($userD->categoryId) ? $userD->categoryId : '';
				$UserDetail['biography'] 		= ($userD->biography) ? $userD->biography : '';
				$UserDetail['estimatePrice'] 	= ($userD->estimatePrice) ? $userD->estimatePrice : '';
				$UserDetail['ACName'] 			= ($userD->ACName) ? $userD->ACName : '';
				$UserDetail['BankName'] 		= ($userD->BankName) ? $userD->BankName : '';
				$UserDetail['AccountNo'] 		= ($userD->AccountNo) ? $userD->AccountNo : '';
				$UserDetail['RoutingNo'] 		= ($userD->RoutingNo) ? $userD->RoutingNo : '';
				
				$UserDetail['device'] 			= ($userD->device) ? $userD->device : '';
				$UserDetail['deviceToken'] 		= ($userD->deviceToken) ? $userD->deviceToken : '';
				$UserDetail['firebaseId'] 		= ($userD->firebaseId) ? $userD->firebaseId : '';
				$UserDetail['averageReview'] 	= ($userD->averageReview) ? $userD->averageReview : '';
				
				$articleArray = array();
				if($val->Specialization !=''){
						$ss = explode(",",$val->Specialization);
						$atriclesD = $this->Specializations->find('all')->where(['Specializations.id IN'=>$ss])->toArray();
							$D=0;
							foreach($atriclesD as $val2){
								$articleArray[$D]['id'] 			= $val2->id;
								$articleArray[$D]['name'] 	= $val2->name;
								$D++;
							}
					}
				$UserDetail['specializations'] = $articleArray;
				$servicesArray = array();
				if($val->TypeOfService !=''){
					$ss2 = explode(",",$val->TypeOfService);
					$ServicesD = $this->Services->find('all')->where(['Services.id IN'=>$ss2])->toArray();
					$D=0;
					foreach($ServicesD as $val3){
						$servicesArray[$D]['id'] 	= $val3->id;
						$servicesArray[$D]['name'] 	= $val3->name;
						$D++;
					}
				}
				$UserDetail['TypeOfService'] = $servicesArray;
				$UserDetail['reviewCount'] 			= $this->Reviews->find()->where(['Reviews.reviewTo'=>$userD->id,'Reviews.status'=>1])->count();;
				$UserDetail['distance'] 			= round(@$this->distance($latitude,$longitude,$userD->lat,$userD->longs),2);
				
				$response['status'] = 'success';
				$response['data'] = $UserDetail;
				
			}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'UserId required';
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
			$reviews = $this->Reviews->newEntity();
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
				$Darray[$i]['reviewId'] 						= ($val->id) ? $val->id : '';
				$Darray[$i]['message'] 							= ($val->message) ? $val->message : '';
				$Darray[$i]['star'] 							= ($val->star) ? $val->star : '';
				$Darray[$i]['created'] 							= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
				
				$Darray[$i]['current_time_zone'] 		= (@$val->current_time_zone) ? @$val->current_time_zone : '';
				//$Darray[$i]['added_time'] 				= (@$val->added_time) ? date('Y-m-d H:i:s',strtotime(@$val->added_time)) : '';
										
				$Darray[$i]['From_userId'] 						= (@$val->reviewfrom->id) ? @$val->reviewfrom->id : '';
				$Darray[$i]['From_firstName'] 					= (@$val->reviewfrom->firstName) ? @$val->reviewfrom->firstName : '';
				$Darray[$i]['From_lastName'] 					= (@$val->reviewfrom->lastName) ? @$val->reviewfrom->lastName : '';
				$Darray[$i]['From_userAddress'] 				= (@$val->reviewfrom->address) ? @$val->reviewfrom->address : '';
				$Darray[$i]['From_profile_picture']				= (@$val->reviewfrom->profile_picture) ? $this->profilePath.@$val->reviewfrom->profile_picture : '';
				
				$Darray[$i]['To_userId'] 						= (@$val->reviewto->id) ? @$val->reviewto->id : '';
				$Darray[$i]['To_firstName'] 					= (@$val->reviewto->firstName) ? @$val->reviewto->firstName : '';
				$Darray[$i]['To_lastName'] 						= (@$val->reviewto->lastName) ? @$val->reviewto->lastName : '';
				$Darray[$i]['To_userAddress'] 					= (@$val->reviewto->address) ? @$val->reviewto->address : '';
				$Darray[$i]['To_profile_picture']				= (@$val->reviewto->profile_picture) ? $this->profilePath.@$val->reviewto->profile_picture : '';
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



	function reviewproduct(){
		if($this->request->getData['productId'] !='' && $this->request->getData['reviewFrom'] !='' && $this->request->getData['star'] != '' && $this->request->getData['message'] !=''){
				
			$this->loadModel('Reviews');
			$this->loadModel('Users');$this->loadModel('Products');
			// $reviews = $this->Reviews->find()->where(['Reviews.bookingId'=>$this->request->getData['bookingId'],'Reviews.reviewTo'=>$this->request->getData['reviewTo'],'Reviews.reviewFrom'=>$this->request->getData['reviewFrom']])->first();
			// if(@$reviews->id !=''){
			// 	$response['status'] = 'Fail'; 
			// 	$response['msg'] = 'Revirew already submited.';
			// 	return $response;
			// }
			$reviews = $this->Reviews->newEntity();
			$reviews = $this->Reviews->patchEntity($reviews, $this->request->getData, ['validate' => false]);
			$reviews['created'] 	= date('Y-m-d H:i:s');
			$reviews['status'] 	= '1';
			$reviews['added_from'] = 'WED';
			//$reviews['added_time'] = date('Y-m-d H:i:s',strtotime(@$this->request->getData['added_time']));
			$this->Reviews->save($reviews);
			
			$query = $this->Reviews->find()->where(['Reviews.productId'=>$this->request->getData['productId']]);
			$avg = $query->select(['averagerating' => $query->func()->avg('star')])->toArray();
			//echo "<pre>"; print_r($avg);
			//echo $avg[0]->averagerating;
			//die;
			
			$ABG['AVGRating'] = round($avg[0]->averagerating,2);
			$users = $this->Products->find()->where(['Products.id'=>$this->request->getData['productId']])->first();
			$users = $this->Products->patchEntity($users, $ABG, ['validate' => false]);
			$this->Products->save($users);

			$response['status'] = 'Success'; 
			$response['msg'] = "Review submited successfully.";
		}else{
			$response['status'] = 'Fail'; 
			$response['msg'] = 'productId, review_from, star and message are required.';
		}
		return $response;
	}
	
	private function reviewproductlist($userId=NULL, $productId=NULL){
		$this->loadModel('User');
		$this->loadModel('Reviews');
		@$USERID = ($userId) ? $userId : $this->request->getData('userId');
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		@$ProductID = ($productId) ? $productId : $this->request->getData['productId'];
		if($pageNo !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['Reviews.id' => 'desc'],
				'page'=>$pageNo,
				'contain' => ['Products','Reviewfroms']
			];
			$keyword = $this->request->query('keyword');
			if(@$USERID !=''){
				$condition[] = ['Reviews.reviewFrom'=>$USERID];
			}
			if(@$ProductID !=''){
				$condition[] = ['Reviews.productId'=>$ProductID];
			}
			$condition[] = ['Reviews.status'=>1];
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
				$Darray[$i]['reviewId'] 						= ($val->id) ? $val->id : '';
				$Darray[$i]['message'] 							= ($val->message) ? $val->message : '';
				$Darray[$i]['star'] 							= ($val->star) ? $val->star : '';
				$Darray[$i]['created'] 							= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
				
				$Darray[$i]['From_userId'] 						= (@$val->reviewfrom->id) ? @$val->reviewfrom->id : '';
				$Darray[$i]['From_firstName'] 					= (@$val->reviewfrom->firstName) ? @$val->reviewfrom->firstName : '';
				$Darray[$i]['From_lastName'] 					= (@$val->reviewfrom->lastName) ? @$val->reviewfrom->lastName : '';
				$Darray[$i]['From_userAddress'] 				= (@$val->reviewfrom->address) ? @$val->reviewfrom->address : '';
				$Darray[$i]['From_profile_picture']				= (@$val->reviewfrom->profile_picture) ? $this->profilePath.@$val->reviewfrom->profile_picture : '';
				
				$Darray[$i]['product_id'] 						= (@$val->product->id) ? @$val->product->id : '';
				$Darray[$i]['product_name'] 					= (@$val->product->productName) ? @$val->product->productName : '';
				$Darray[$i]['product_image'] 					= (@$val->product->image) ? $this->productPath.@$val->product->image : '';
				$Darray[$i]['product_unitPrice'] 				= (@$val->product->unitPrice) ? @$val->product->unitPrice : '';
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

	function countrylist(){
			$this->loadModel('Countries');
			$parentCountryList = $this->Countries->find()
																					//->where(['Categories.status'=>'0'])
			->order(['Countries.name'=>'ASC'])->toArray();
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
	
	
	function homequestion(){
		$this->loadModel('Homeservicesquestions');
		$parentCountryList = $this->Homeservicesquestions->find()->where(['Homeservicesquestions.status'=>1])->order(['Homeservicesquestions.question'=>'ASC'])->toArray();
		$PC = array();
		foreach($parentCountryList as $key=>$VV){
			$PC['id'] = $VV->id;
			$PC['question'] = $VV->question;
			$PC['option1'] = $VV->option1;
			$PC['option2'] = $VV->option2;
			$list[] = $PC;
		}
		$response['status'] = "success";
		$response['data'] = $list;
		return $response;
		
	}

	function typeofpet(){
			$this->loadModel('Typeofpets');
			$parentCountryList = $this->Typeofpets->find()->where(['Typeofpets.status'=>1])->order(['Typeofpets.name'=>'ASC'])->toArray();
			$PC = array();
			foreach($parentCountryList as $key=>$VV){
				$PC['id'] = $VV->id;
				$PC['name'] = $VV->name;
				$list[] = $PC;
			}
			$response['status'] = "success";
			$response['data'] = $list;
			return $response;
			
	}
	
	function typeofbusiness(){
			$this->loadModel('Typeofbusines');
			$TYPE = @$this->request->getData('type');//Other  // Veterinarian
			$parentCountryList = $this->Typeofbusines->find()->where(['Typeofbusines.type'=>$TYPE,'Typeofbusines.status'=>1])->order(['Typeofbusines.name'=>'ASC'])->toArray();
			// if($TYPE == 'Other'){
			// 	$parentCountryList = $this->Typeofbusines->find()->where(['Typeofbusines.type'=>$TYPE])->order(['Typeofbusines.name'=>'ASC'])->toArray();
			// }else{
			// 	$parentCountryList = $this->Typeofbusines->find()->where(['Typeofbusines.type'=>'Veterinarian'])->order(['Typeofbusines.name'=>'ASC'])->toArray();
			// }
			
			$PC = array();
			foreach($parentCountryList as $key=>$VV){
				$PC['id'] = $VV->id;
				$PC['name'] = $VV->name;
				$list[] = $PC;
			}
			$response['status'] = "success";
			$response['data'] = (@$list) ? @$list : array();
			return $response;
	}
				
	function typeofservice(){
			$this->loadModel('Typeofservices');
			$parentCountryList = $this->Typeofservices->find()->where(['Typeofservices.status'=>1,'Typeofservices.UTYPE'=>$this->request->getData['UTYPE']])->order(['Typeofservices.name'=>'ASC'])->toArray();
			$PC = array();
			foreach($parentCountryList as $key=>$VV){
				$PC['id'] = $VV->id;
				$PC['name'] = $VV->name;
				$PC['per'] = $VV->per;
				$list[] = $PC;
			}
			$response['status'] = "success";
			$response['data'] = (@$list) ?  @$list : array();
			return $response;
	}
				
	function typeofwalking(){
			$this->loadModel('Typeofwalkings');
			$parentCountryList = $this->Typeofwalkings->find()->where(['Typeofwalkings.status'=>1])->order(['Typeofwalkings.name'=>'ASC'])->toArray();
			$PC = array();
			foreach($parentCountryList as $key=>$VV){
				$PC['id'] = $VV->id;
				$PC['name'] = $VV->name;
				$list[] = $PC;
			}
			$response['status'] = "success";
			$response['data'] = $list;
			return $response;
	}
				
	function specilazation(){
			$this->loadModel('Specializations');
			$parentCountryList = $this->Specializations->find()
																					//->where(['Categories.status'=>'0'])
			->order(['Specializations.name'=>'ASC'])->toArray();
			$PC = array();
			foreach($parentCountryList as $key=>$VV){
				$PC['id'] = $VV->id;
				$PC['name'] = $VV->name;
				$list[] = $PC;
			}
			$response['status'] = "success";
			$response['data'] = $list;
			return $response;
	}
	
	function statelist($counttyId = NULL){
		$this->loadModel('States');
		$counttyID = ($counttyId) ? $counttyId : @$this->request->getData('counttyId');
		if(@$this->request->getData['state_name'] !=''){
			$parentCountryList = $this->States->find()->where(['States.country_id'=>$counttyID,'States.name Like'=>$this->request->getData['state_name'].'%'])->order(['States.name'=>'ASC'])->toArray();
		}else{
			$parentCountryList = $this->States->find()->where(['States.country_id'=>$counttyID])->order(['States.name'=>'ASC'])->toArray();
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
		$this->loadModel('Cities');
		$counttyID = ($counttyId) ? $counttyId : @$this->request->getData('state_id');
		$parentCountryList = $this->Cities->find()->where(['Cities.state_id'=>$counttyID])->order(['Cities.name'=>'ASC'])->toArray();
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

	function logout(){
		$articles = TableRegistry::get('Users');
		$query = $articles->query();
		$query->update()
			->set(['deviceToken'=>'','lastLogin'=>date('Y-m-d H:i:s')])
			->where(['id' => $this->request->getData['userId']])
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
		$this->loadModel('Users');
		if($this->request->getData('userId')){
			$users = $this->Users->find()->Where(['Users.id' => $this->request->getData('userId')])->first();
			$check = (new DefaultPasswordHasher)->check($this->request->getData('oldPassword'), $users->password);
			if($check){
				$users = $this->Users->patchEntity($users, ['password'=> $this->request->getData('newPassword'),'Pcode' => base64_encode($this->request->getData['newPassword'])],['validate' => false]);
				$this->Users->save($users);
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
		$this->loadModel('Users');
		if($this->request->getData('email')){
			$users = $this->Users->find()->Where(['Users.email' => $this->request->getData('email')])->first();
			//$OLDPASS = base64_decode($users->Pcode);
			//$check = (new DefaultPasswordHasher)->check($this->request->getData('oldPassword'), $users->password);
			if(!empty($users)){
				$newPasss = rand(1111111,99999999);
				$token = $this->random_password(50);
				//echo $token; die;
				$resetUrl = Configure::read('App.siteurl') . 'users/reset/' . $token;
				
				$users->passwordToken = $token;
				$this->Users->save($users);
				$to = $users->email;
				//$to = 'raushan.kumar@evirtualservices.com';
				$subject = 'Ritevet-Reset Password '.$to;
				$message = "Dear " . ucfirst($users->firstName) ." ". ucfirst($users->lastName).",<br>";
				$message .= "To reset your password, please <a href='" . $resetUrl . "'>click here</a>.";
				
				$this->phpemail($to,$subject,$message);
				//echo mail('rksuri2@gmail.com', 'HI', 'HELLO'); die('+++');
				$response['status'] = 'success';
				$response['email'] = $users->email;
				
				$response['msg'] = 'Password sent to the registered email.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'Email does not exist with Ritevet.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'email is required.';
		}
		return  $response;
	}
	
	function help(){
		$data['phone'] 	= '180-234-5678';
		$data['eamil'] = 'support@ritevet.com';
		$response['status'] = 'success';
		$response['data'] = $data;
		return  $response;
	}
	
	
	
	function category(){
		$this->loadModel('Categories');
		$conditons = array('Categories.status' => '1' , 'Categories.parentId' => 0);
		if(isset($this->request->getData['keyword']) && $this->request->getData['keyword'] !=''){
			$conditons = array('Categories.name LIKE' =>'%'.$this->request->getData['keyword'].'%','Categories.status' => '1' , 'Categories.parentId' => 0);
		}
		$cat=$this->Categories->find('all')->where($conditons)->contain(['Products'=>function ($q) {
			return $q->where(['Products.status' => 1]);
		}])->select(['Categories.id','Categories.name','Categories.image','Categories.bigImage'])->toArray();
		$CATEArray=array();
		$i=0;
		//pr($cat); die;
		//$cat=$this->array_filter_recursive($cat);
		foreach($cat as $v){
			$CATEArray[$i]['id'] =  $v->id;
			$CATEArray[$i]['name'] = $v->name;
			$CATEArray[$i]['image'] = $v->image;
			$CATEArray[$i]['totalProduct'] = count($v->products);
			if(@$this->request->getData['freestuff'] == 1){
				$sub = $this->Categories->find('all')->where(["Categories.parentId" => $v->id, 'Categories.status' => '1'])->contain(['Subproducts'=>function ($q) {
					return $q->where(['Subproducts.status' => 1, 'Subproducts.unitPrice' => 0]);
				}])->select(['Categories.id','Categories.name','Categories.image','Categories.bigImage'])->toArray();
			}else{
				$sub = $this->Categories->find('all')->where(["Categories.parentId" => $v->id, 'Categories.status' => '1'])->contain(['Subproducts'=>function ($q) {
					return $q->where(['Subproducts.status' => 1, 'Subproducts.unitPrice >' => 0]);
				}])->select(['Categories.id','Categories.name','Categories.image','Categories.bigImage'])->toArray();
			}
			//$CATEArray[$i]["SubCat"] = $sub;
			$subb = array();
			foreach($sub as $keyy=>$vc){
				$subb[$keyy]['id'] 			= $vc->id;
				$subb[$keyy]['name'] 		= $vc->name;
				$subb[$keyy]['image'] 		= $vc->image;
				$subb[$keyy]['totalProduct']= count($vc->subproducts);
			}
			$CATEArray[$i]["SubCat"] = ($subb) ? $subb : array();
			$i++; 
		}
		$response['status'] = "success";
		$response['bseUrl'] = $this->catPath;
		$response['response'] = ($CATEArray) ? $this->array_filter_recursive($CATEArray) : array();
		return $response; 
	}
	
	//function subcategory(){
	//	$this->loadModel('Categories');
	//	$conditons = array('Categories.status' => '1' , 'Categories.parentId' => $this->request->getData['parentId'] );
	//	$cat=$this->Categories->find('all')->where($conditons)->select(['Categories.id','Categories.name','Categories.image','Categories.bigImage'])->toArray();
	//	$CATEArray=array();
	//	$i=0;
	//	//$cat=$this->array_filter_recursive($cat);
	//	foreach($cat as $v){
	//		$CATEArray[$i]['id'] =  $v->id;
	//		$CATEArray[$i]['name'] = ($v->name) ? $v->name : '';
	//		$i++; 
	//	}
	//	$response['status'] = "success";
	//	$response['response'] = ($CATEArray) ? $this->array_filter_recursive($CATEArray) : array();
	//	return $response; 
	//}
	
	
	
	
	//(array) $object;  //Object to array();
	//function array_filter_recursive_object($array) {
	function array_filter_recursive_object(array &$array){
		foreach ($array as $key => &$value) {
		  if (is_array($value)) {
			$value = $this->array_filter_recursive_object($value);
		  }
		  if (empty($value)) {
			$array[$key] = '';
		  }
		}
		return $array;
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
	
	
	function testmail(){
		//$to=$this->request->getData('email');
		$to='raushan@mailinator.com';
		$subject="Ritevet Registration";
		$message="Dear ";
		$message .= '<br>Your new <strong>Ritevet <span style="color:#FFCC00"></span> Account has been created. Welcome to the <strong>Ritevet <span style="color:#FFCC00"></span></strong> family!';
		$message .= '<br>From now on, please log in to your account using your email address and password';
		$message.='<br><br> Thank you for registering with <strong>Ritevet <span style="color:#FFCC00"></span>. Your credentials is given below:';
		$this->phpemail($to, $subject, $message);
		die('emailsend');
	}
	
	
	function updatepayment(){
		if($this->request->getData['UTYPE'] !='' && $this->request->getData['userInfoId'] !='' && $this->request->getData['userId'] !='' && $this->request->getData['SubscriptionFrom'] !=''){
			//SubscriptionFrom:  // Android   IOS
			$this->loadModel('Users');$this->loadModel('Usersinformations');
			$Previous_day = date("Y-m-d", strtotime("+30 days"));
			//$Previous_day = date('Y-m-d',strtotime($this->request->getData['SubscriptionFrom']." +30 days"));
			
			$articles = TableRegistry::get('Usersinformations');
			$query = $articles->query();
			$query->update()
				->set(['SubscriptionFrom'=>$this->request->getData['SubscriptionFrom'],'purcheseDate'=>date('Y-m-d'),'expiryDate'=>$Previous_day])
				->where(['UTYPE'=>$this->request->getData['UTYPE'],'id' => $this->request->getData['userInfoId']])
				->execute();
			
				$userINFO = $this->Usersinformations->find()->where(['Usersinformations.id'=>$this->request->getData['userInfoId']])->contain(['Users'])->first();
				if(@$userINFO->user->email !=''){
					$to=$userINFO->user->email;
					$subject="Ritevet Subscription";
					$message = "Dear " . ucfirst(@$userINFO->firstName) ." ". ucfirst(@$userINFO->lastName).",<br>";
					$message .= '<br>Your subscription has been updated successfully.';
					$message.='<br>';
					$this->phpemail($to, $subject, $message);
				}
			
			$response['status'] = "success";
			$response['msg'] = 'Subscription updated successfully.';
		}else{
			$response['status'] = 'Fail';
			$response['msg'] = 'userId and amount are required';	
		}
		return $response; 
	}
		
		function cancelsubscription(){
			if($this->request->getData['userId'] !='' && $this->request->getData['stripeSubscriptionId'] !=''){
				
				$Previous_day = date("Y-m-d");
				$articles = TableRegistry::get('Users');
				$query = $articles->query();
				$query->update()
					->set(['ExpiryDate'=>$Previous_day])
					->where(['id' => $this->request->getData['userId']])
					->execute();
				
				$response['status'] = "success";
				$response['msg'] = 'Transaction completed successfully.';
			}else{
				$response['status'] = 'Fail';
				$response['msg'] = 'userId and stripeSubscriptionId are required';	
			}
			return $response; 
		}

		
		
		function getwallet(){
			if($this->request->getData['userId'] !=''){
				$this->loadModel('Users');$this->loadModel('Usersinformations');$this->loadModel('Cashouts');
				$userinfoo = $this->Usersinformations->find()->where(['Usersinformations.userId'=>$this->request->getData['userId'],'Usersinformations.UTYPE'=>2])->contain(['Users'])->first();
				$cahsout = $this->Cashouts->find()->select(['sum' => 'SUM(Cashouts.approved_amount)'])->where(['Cashouts.userId'=>$this->request->getData['userId'], 'Cashouts.status'=>2,'Cashouts.status'=>2])->toArray();
				$userId = $this->request->getData['userId'];
				$calculateCashoutAmount = function ($type) use ($userId) {
					$query = $this->Cashouts->find()->where(['Cashouts.type' => $type, 'Cashouts.userId' => $userId]);
					$result = $query->select(['sumamount' => $query->func()->sum('approved_amount')])->first();
					return round($result && isset($result->sumamount) ? $result->sumamount : 0, 2); // Default to 0 if no result
				};
				$product_cashout_amount = $calculateCashoutAmount(1);
				$vateti_cashout_amount = $calculateCashoutAmount(2);
				$other_pet_cashout_amount = $calculateCashoutAmount(3);
				//pr($cahsout); die;
				$response['status'] = "success";
				
				$response['VeterianWallet'] = (@$userinfoo->wallet) ? @$userinfoo->wallet : 0;
				$response['VeterianWallet_total'] = $response['VeterianWallet'] + $vateti_cashout_amount;
				$response['productWallet_accoun_detail'] = 'Yes';
				$response['VeterianWallet_accoun_detail'] = 'Yes';
				$userinfoo2 = $this->Users->find()->where(['Users.id'=>$this->request->getData['userId']])->first();
				$response['productWallet'] = (@$userinfoo2->wallet) ? @$userinfoo2->wallet : 0;
				$response['productWallet_total'] = $response['productWallet'] + $product_cashout_amount;
				if(@$userinfoo2->AccountName =='' || @$userinfoo2->accountNo =='' || @$userinfoo2->RoutingNo =='' || @$userinfoo2->accountType==''){
					$response['productWallet_accoun_detail'] = 'No';
				}
				if(@$userinfoo->AccountNo =='' || @$userinfoo->RoutingNo =='' || @$userinfoo->accountType =='' || @$userinfoo->ACName==''){
					$response['VeterianWallet_accoun_detail'] = 'No';
				}
				$response['msg'] = '';
			}else{
				$response['status'] = 'Fail';
				$response['msg'] = 'userId is required';	
			}
			return $response; 
		}
		
		function cashoutrequest(){
			if($this->request->getData['userId'] !='' && $this->request->getData['requested_amount'] !='' && $this->request->getData['type'] !=''){
				$this->loadModel('Cashouts');$this->loadModel('Users');
				$Cashouts = $this->Cashouts->newEntity();
				$Cashouts = $this->Cashouts->patchEntity($Cashouts, $this->request->getData, ['validate' => false]);
				$Cashouts['created'] = date('Y-m-d H:i:s');
				//$Cashouts['added_time'] = date('Y-m-d H:i:s',strtotime(@$this->request->getData['added_time']));
				$this->Cashouts->save($Cashouts);
				
				
				$to= Configure::read('App.adminEmail');
				//$to='raushan.kumar@evirtualservices.com';
				$subject="Ritevet Cashout";
				$message="Dear Admin";
				$message .= '<br>One service provider has requested for cashout amount of <strong>$'.$this->request->getData['requested_amount'].'</strong>';
				$message .= '<br>';
				$message.='<br>Please check details in admin section and do the needfull.';
				$this->phpemail($to, $subject, $message);
								
				$response['status'] = "success";
				$response['msg'] = 'Request sent successfully.';
			}else{
				$response['status'] = 'Fail';
				$response['msg'] = 'userId requested_amount and type are required';	
			}
			return $response; 
		}
		
		
		function cashoutlist($user_id=NULL,$type=NULL){
			$this->loadModel('Users');
			$this->loadModel('Cashouts');
			$userId = ($user_id) ? $user_id : $this->request->getData('userId');
			$type = ($type) ? $type : @$this->request->getData['type'];
			$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : '1';
			if($userId !=''){
				$this->paginate = [
					'limit' => '10',//$limit,
					'order' => ['Cashouts.id' => 'desc'],
					'page'=>$pageNo,
					//'contain'=>['Resturents','Categories']
				];
				if($userId != ''){
					$condition[] = ['Cashouts.userId' => $userId];
				}
				if($type != ''){
					$condition[] = ['Cashouts.type' => $type];
				}
				$userservices =array();    
				$query = $this->Cashouts->find('all')->where($condition);
				$totalCount =  $query->count();
				if($pageNo <= ceil($totalCount/10)){
					$userservices = $this->paginate($query);
					$userservices = $userservices->toArray();
				}
				$CATArray = array();
				$Darray = array();
				$i=0;
				//echo "<pre>"; print_r($userservices); die;
				foreach($userservices as $userD){
					$Darray[$i]['requestId']    	= ($userD->id) ? $userD->id : '';
					$Darray[$i]['requested_amount']	= ($userD->requested_amount) ? $userD->requested_amount : 0;
					$Darray[$i]['approved_amount']	= ($userD->approved_amount) ? $userD->approved_amount : 0;
					$Darray[$i]['sendDate'] 		= ($userD->approve_time) ? date('Y-m-d H:i:s', strtotime($userD->approve_time)) : '';
					//$Darray[$i]['approve_time_zone']= ($userD->approve_time_zone) ? $userD->approve_time_zone : '';
					$Darray[$i]['created'] 			= ($userD->created) ? date('Y-m-d H:i:s', strtotime($userD->created)) : '';
					$Darray[$i]['comment']			= ($userD->comment) ? $userD->comment : '';
					$Darray[$i]['type']				= ($userD->type) ? $userD->type : '';

					$Darray[$i]['timezone'] = $this->timezone();
					$Darray[$i]['current_time_zone']= (@$userD->current_time_zone) ? @$userD->current_time_zone : '';
					$Darray[$i]['added_time'] 		= (@$userD->added_time) ? date('Y-m-d H:i:s',strtotime(@$userD->added_time)) : '';

					$i++;
				}
				$response['status'] = 'success';
				$response['data'] = array_values($this->array_filter_recursive($Darray));
		
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'UserId is required';
			}
			return $response;
		}
		
		
		
		function american_board_certified_option(){
		
			$OPT = [1=>'Shelter medicine', 2=>'Reptile and amphibian',3=>'Exotic companion mammal',4=>'Canine and feline',5=>'Equine',
							    6=>'Fish (provisionally recognized March 2022)', 7=>'Food animal',8=>'Dairy',9=>'Swine health management',10=>'Avian',
							    11=>'Beef cattle', 12=>'Feline',13=>'Toxicology ',14=>'Cardiology',15=>'Small animal internal medicine',
							    16=>'Large animal internal medicine', 17=>'Neurology',18=>'Oncology ',19=>'Nutrition',20=>'Virology',
							    21=>'Immunology', 22=>'Bacteriology/Mycology',23=>'Parasitology ',24=>'Anatomic pathology',25=>'Clinical pathology',
							    26=>'Epidemiology', 27=>'Radiation oncology',28=>'Equine diagnostic imaging ',29=>'Canine',30=>'Equine',
							    31=>'Small animal surgery', 32=>'Large animal surgery',33=>'Equine dental'
							   ];
			$array = array();$i=0;
			foreach($OPT as $K=>$VV){
				$array[$i]['id'] = $K;
				$array[$i]['name'] = $VV;
				$i++;
			}
			$response['status'] = 'success';
			$response['data'] = $array;
			return $response;
		}
		
		
	
		
				
		function callhistoryadd(){
			if($this->request->getData['callerId'] !='' && $this->request->getData['receiverId'] !='' && $this->request->getData['duration'] !='' && $this->request->getData['callerType'] != '' ){
					
				$this->loadModel('Callhistories');
				$callhistory = $this->Callhistories->newEntity();
				$callhistory = $this->Callhistories->patchEntity($callhistory, $this->request->getData, ['validate' => false]);
				$callhistory['created'] 	= date('Y-m-d H:i:s');
				$callhistory['status'] 	= '1';
				$this->Callhistories->save($callhistory);
							
				$response['status'] = 'Success'; 
				$response['msg'] = "Call history added successfully.";
			}else{
				$response['status'] = 'Fail'; 
				$response['msg'] = 'callerId,receiverId, duration and callerType are required.';
			}
			return $response;
		}
		
		private function callhistorylist($userId=NULL){
			$this->loadModel('Callhistories');
			@$USERID = ($userId) ? $userId : $this->request->getData('userId');
			@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
			if($USERID !=''){
				$this->paginate = [
					'limit' => '10',//$limit,
					'order' => ['Callhistories.id' => 'desc'],
					'page'=>$pageNo,
					'contain' => ['Callers','Receivers']
				];
				$keyword = $this->request->query('keyword');
				$condition[] = ['OR'=>[
										['Callhistories.callerId'=>$USERID],
										['Callhistories.receiverId'=>$USERID]
									]
								];
				$userservices = array();    
				$query = $this->Callhistories->find('all')->where($condition);
				$totalCount =  $query->count();
				if($pageNo <= ceil($totalCount/10)){
					$userservices = $this->paginate($query);
					$userservices = $userservices->toArray();
				}
				$Darray = array();
				$i=0;
				//echo "<pre>"; print_r($userservices); die;
				foreach($userservices as $val){
					$Darray[$i]['callhistoryId'] 						= ($val->id) ? $val->id : '';
					$Darray[$i]['callerId'] 						= ($val->callerId) ? $val->callerId : '';
					$Darray[$i]['receiverId'] 						= ($val->receiverId) ? $val->receiverId : '';
					$Darray[$i]['duration '] 						= ($val->duration ) ? $val->duration  : '';
					
					$Darray[$i]['Caller_userName'] 					= (@$val->caller->fullName) ? @$val->caller->fullName : '';
					//$Darray[$i]['Caller_device'] 					= (@$val->caller->device) ? @$val->caller->device : '';
					//$Darray[$i]['Caller_deviceToken'] 				= (@$val->caller->deviceToken) ? @$val->caller->deviceToken : '';
					$Darray[$i]['Caller_profile_picture']				= (@$val->caller->profile_picture) ? $this->profilePath.@$val->caller->profile_picture : '';
					
					$Darray[$i]['Receiver_userName'] 						= (@$val->receiver->fullName) ? @$val->receiver->fullName : '';
					//$Darray[$i]['Receiver_device'] 					= (@$val->receiver->device) ? @$val->receiver->device : '';
					//$Darray[$i]['Receiver_deviceToken'] 					= (@$val->receiver->deviceToken) ? @$val->receiver->deviceToken : '';
					$Darray[$i]['Receiver_profile_picture']				= (@$val->receiver->profile_picture) ? $this->profilePath.@$val->receiver->profile_picture : '';
					
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


		function callhistorydelete(){
			if($this->request->getData['callerId'] !='' && $this->request->getData['receiverId'] !='' && $this->request->getData['callhistoryId'] !='' ){
				$this->loadModel('Callhistories');
				$callhistory = $this->Callhistories->find()->where(['Callhistories.id'=>$this->request->getData['callhistoryId'],'Callhistories.receiverId'=>$this->request->getData['receiverId'],'Callhistories.callerId'=>$this->request->getData['callerId']])->first();
				if(@$callhistory->id !=''){
					$this->Callhistories->delete($callhistory);
					$response['status'] = 'Success'; 
					$response['msg'] = "Call history deleted successfully.";
				}else{
					$response['status'] = 'Fail'; 
					$response['msg'] = 'No longer available.';
				}
			}else{
				$response['status'] = 'Fail'; 
				$response['msg'] = 'callerId,receiverId and callhistoryId are required.';
			}
			return $response;
		}


		function deleteotp(){
			if($this->request->getData('userId') !=''){
				$this->loadModel('Users');
				$usr = $this->Users->find()->where(['Users.id'=>$this->request->getData('userId')])->first();
				if(@$usr->id != ''){
					$OTP = rand(111111,999999);
					$articles = TableRegistry::get('Users');
						$query = $articles->query();
						$query->update()
						->set(['passwordToken' =>$OTP])
						->where(['id' => $usr->id])
						->execute();
					//$MSG = 'Pludin code '.$OTP;
					//$number = trim($usr->countryCode).substr(trim($usr->contactNumber),-10);
					//$this->sendsmsnew($number,$MSG);
					
					$to = $usr->email;
					$subject = 'Ritevet-Delete account OTP for '.$usr->email;
					$message="Dear ".ucfirst($usr->fullName);
					$message.="<br><br> You have request for the Delete account, use below token to delete account:";
					
					$message.='<br><br>OTP: '.$OTP;
					$this->phpemail($to,$subject,$message);
					
					$response['status'] = 'success';
					$response['msg'] = 'OTP sent to the registered email address';
				}else{
					$response['status'] = 'Fails';
					$response['msg'] = 'User not verfified.';
				}
			}
				return  $response;
		}
		
		function userdelete(){
			$this->loadModel('Users');
			if($this->request->getData['userId'] !=''){
				if(@$this->request->getData['OTP'] !=''){
					$users = $this->Users->find()->where(['Users.id'=>$this->request->getData['userId']])->first();
					if($users->passwordToken == $this->request->getData['OTP']){
						$articles = TableRegistry::get('Users');
						$query = $articles->query();
						$query->update()
						->set(['status' =>0])
						->where(['id' => $this->request->getData['userId']])
						->execute();
						$response['status'] = "success";
						$response['msg'] = 'User deleted successfully.';
					}else{
						$response['status'] = 'Fail'; 
						$response['msg'] = 'OTP not valid.';
					}
				}else{
					$articles = TableRegistry::get('Users');
						$query = $articles->query();
						$query->update()
						->set(['status' =>0])
						->where(['id' => $this->request->getData['userId']])
						->execute();
					$response['status'] = "success";
					$response['msg'] = 'User deleted successfully.';
				}
			}else{
				$response['status'] = 'Fail'; 
				$response['msg'] = 'UserId required.';
			}
			return  $response;
		}



	public function addmultiimage(){
		$this->loadModel('Images');
		if ($this->request->getData['userId'] != '' && $this->request->getData['UTYPE'] != '' && $this->request->getData['userInfoId'] != '' && $this->request->getData['imageType'] != '') {
			if (@$_FILES['multiImage']['error']) {
				foreach (@$_FILES['multiImage']['error'] as $key => $val) {
					if ($val == '0') {
						$DATA['userId'] 		= $this->request->getData['userId'];
						$DATA['UTYPE'] 			= $this->request->getData['UTYPE'];
						$DATA['userInfoId'] 	= $this->request->getData['userInfoId'];
						$DATA['imageType'] 		= $this->request->getData['imageType'];
						$DATA['image'] = '';
						$fileName1 = '';
						if (@$_FILES['multiImage']['name'][$key] != '') {
							$fileName1 = time() . stripslashes($_FILES['multiImage']['name'][$key]);
							$fileName1 = str_replace(" ", "", $fileName1);
							$DATA['image'] = $fileName1;
						}
						$galleries = $this->Images->newEntity();
						$galleries = $this->Images->patchEntity($galleries, $DATA, ['validate' => false]);
						$galleries['created'] 	= date('Y-m-d H:i:s');
						$galleries['status'] 	= '1';
						$this->Images->save($galleries);
						if (@$_FILES['multiImage']['name'][$key] != '' && $fileName1 != '') {
							$target = WWW_ROOT . '/img/uploads/multiimage/' . $fileName1;
							$source = $_FILES['multiImage']['tmp_name'][$key];
							move_uploaded_file($source, $target);
						}
					}
				}
				$response['status'] = 'success';
				$response['msg'] = 'Image uploaded successfully.';
			} else {
				$response['status'] = 'Fails';
				$response['msg'] = 'userId, bookingid and image are required.';
			}
		} else {
			$response['status'] = 'Fails';
			$response['msg'] = 'userId, bookingid and image are required.';
		}
		return  $response;
	}

	function addmultiimageIOS(){
		$this->loadModel('Images');
		if ($this->request->getData['userId'] != '' && $this->request->getData['UTYPE'] != '' && $this->request->getData['userInfoId'] != '' && $this->request->getData['imageType'] != '') {
			if(@$_FILES['multiImage']['error']){
				@mkdir(WWW_ROOT.'img/uploads/table/'.$this->request->getData['userId']);
				foreach(@$_FILES['multiImage']['error'] as $key=>$val){
					if($val == '0'){
						$DATA['userId'] 			= $this->request->getData['userId'];
						$DATA['clubTableId'] 		= (@$this->request->getData['clubTableId']) ? @$this->request->getData['clubTableId'] : '';
						$DATA['image'] = '';
						$fileName1 = '';
						if(@$_FILES['multiImage']['name'][$key] !=''){
							$fileName1=time().stripslashes($_FILES['multiImage']['name'][$key]);
							$fileName1= str_replace(" ","",$fileName1);
							$DATA['image']=$fileName1;
						}
						$galleries = $this->Clubphotos->newEntity();
						$galleries = $this->Clubphotos->patchEntity($galleries, $DATA, ['validate' => false]);
						$galleries['created'] 	= date('Y-m-d H:i:s');
						$galleries['status'] 	= '1';
						$this->Clubphotos->save($galleries);
						if(@$_FILES['multiImage']['name'][$key] !='' && $fileName1 !=''){
							$target = WWW_ROOT.'/img/uploads/table/'.$this->request->getData['userId'].'/'.$fileName1;
							$source=$_FILES['multiImage']['tmp_name'][$key];
							move_uploaded_file($source,$target);
						}
					}
				}
				$response['status'] = 'success';
				$response['msg'] = 'Image uploaded successfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'userId, bookingid and image are required.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'userId, bookingid and image are required.';
		}
		return  $response;
	}

	function multiimagedelete(){
		if ($this->request->getData['userId'] != '' && $this->request->getData['multiimageId'] != '' && $this->request->getData['imageType'] != '') {
			$this->loadModel('Images');
			$galleries = $this->Images->find()->where(['Images.userId'=>$this->request->getData['userId'],'Images.id'=>$this->request->getData['multiimageId'],'Images.imageType'=>$this->request->getData['imageType']])->first();
			if($galleries->id !=''){
				@unlink(WWW_ROOT . 'img/uploads/multiimage/'.$galleries->image);
				$this->Images->delete($galleries);
				$response['status'] = 'success';
				$response['msg'] = 'Image uploaded successfully.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'No longer available.';
			}
		} else {
			$response['status'] = 'Fails';
			$response['msg'] = 'userId, bookingid and image are required.';
		}
		return  $response;
	}


	function timezone(){
		//$datee = '2023-12-29 03:29:11';
		//$dateTime1 = date('Y-m-d H:i:s',strtotime($datee));
		$dateTime1 = date('Y-m-d H:i:s');
		$utc_time = gmdate("Y-m-d  H:i:s");
		//$date_utc = date_default_timezone_get();

		$fromTime = strtotime($dateTime1);
		$toTime = strtotime($utc_time);
		$minutes_difference = (int)(($fromTime - $toTime) / 60);
		$hours = round($minutes_difference/60,1);
		//echo "<br>";
		$TO = (int)($minutes_difference/60);
		$min = $minutes_difference - ($TO*60);
		//sprintf("%02d", $num)
		if($fromTime >= $toTime){
			$hours = '+'.sprintf("%02d", $TO).':'.$min;
		}else{
			$hours = '-'.sprintf("%02d", $TO).':'.$min;
		}
		
		$response['timezome'] = date_default_timezone_get();
		$response['UTC_GMT'] = $hours;
		return $response;

	}

	function testtime(){
		//echo $now_utc = datetime.now(timezone('UTC'));

		echo $utc_date = DateTime::createFromFormat(
			'Y-m-d H:i',
			'2011-04-27 02:45',
			new DateTimeZone('UTC')
		);

		die;
	}

	function currenttime(){
		echo date('Y-m-d H:i:s');
		date_default_timezone_set('Asia/Kolkata');
		echo date_default_timezone_get() . ' => ' . date('e') . ' => ' . date('T'). ' => ' . date('U'). ' => ' . date('Z'). ' => ' . date('P');
		echo "<br>";
		date_default_timezone_set('America/Los_Angeles');
		echo date_default_timezone_get() . ' => ' . date('e') . ' => ' . date('T'). ' => ' . date('U'). ' => ' . date('Z'). ' => ' . date('P');
	}


	function savenotification($array = array()){
		$this->loadModel('Notifications');
		$reviews = $this->Notifications->newEntity();
		$reviews = $this->Notifications->patchEntity($reviews, $array, ['validate' => false]);
		$reviews['created'] 	= date('Y-m-d H:i:s');
		$this->Notifications->save($reviews);
		return true;
	}

	function notificationtest(){
		//phpinfo();
		$bodyU['message'] 		= 'Your payment of 2000 for your booking successfully processed.';
		$bodyU['type'] 			= 'Payment';
		$eviceToken = "dq0bWRKzQByWwKqjj8zgon:APA91bERW_mwZIODGYvZTnGj1wEn8PiwjPeCiLFVy0FLWpYxFfpxmjz89rkKTggWoK5irbwzsdnjZui4YxHqDpNBJBPaohfGanhaiCV7bK2xWCGRxnlWVBc";
		if(true){
			$this->sednIosPushNotification(@$eviceToken,$bodyU);
		}else{
			$this->sendNotificationOnAndroid(@$eviceToken,$bodyU);
		}
	}

	function oldtonewtimezone($severTimeZone=NULL, $clinetTimeZone=NULL,$currentTime=NULL){
		$verdor_sign = substr(@$severTimeZone, 0, 1);
		$customer_sign = substr(@$clinetTimeZone, 0, 1);
		$Vendor_time_Zone = explode(":",substr(@$severTimeZone,1));
		$customer_time_Zone = explode(":",substr(@$clinetTimeZone,1));
		$vendor_total_minute = $Vendor_time_Zone[0] * 60 + $Vendor_time_Zone[1];
		$customer_total_minute = $customer_time_Zone[0] *60 + $customer_time_Zone[1];
		if($verdor_sign == $customer_sign){
			$Final = $customer_total_minute - $vendor_total_minute;
			$sign = '-';
			if($customer_total_minute >= $vendor_total_minute){
				$sign = '+';
			}
			//$time = $sign.intdiv($Final, 60).':'. ($Final - (intdiv($Final, 60) *60));
		}else{
			$Final = $customer_total_minute + $vendor_total_minute;
			$sign = $customer_sign;
			//$time = $sign.intdiv($Final, 60).':'. ($Final - (intdiv($Final, 60) *60));
		}
		$CAL = abs($Final*60);
		if($sign == '+'){
			$NOW_start = strtotime($currentTime)+$CAL;
		}else{
			$NOW_start = (strtotime($currentTime)-$CAL);
		}
		return $NOW_start;
	}


	function newtest(){
		$this->loadModel('Carts');
		$cart_Details = $this->Carts->find()->where(['Carts.userId' => 47, 'Carts.orderId is'=>null])->toArray();
		//pr($cart_Details); die('GOOD');
		$articles = TableRegistry::get('Carts');
		$query = $articles->query();
		$query->update()
			->set(['orderId' => 80])
			->where(['userId' => 47, 'orderId is'=>null])
			->execute();
		die('yeeee');
	}


	public function getTaxRate($zip=NULL) 
    {
        // API LIVE URL
        // $url = "https://api.taxjar.com/v2/rates/" . $zip;
        
		$zips = (@$zip) ? @$zip : @$this->request->getData['zip'];
        // API SANDBOX URL
        $url = "https://api.sandbox.taxjar.com/v2/rates/" . $zips;
        
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer fae6e131110ae6c428c5f90af563c396', // sandbox
            'Content-Type: application/json'
        ]);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            $result = ['status' => 'error', 'message' => $error_msg];
            echo json_encode($result);
            exit;
        }
    
        curl_close($ch);
        $result = json_decode($response, true);
    
        if (isset($result['status']) && $result['status'] != 200) {
            $result = ['status' => 'error', 'message' => $result['error']];
            echo json_encode($result);
            exit;
        }
        
        echo json_encode(['status' => 'success', 'rate' => $result['rate']]);
        if(!empty($orderId)){}else{exit;}
    }


	function elarning_category(){
		$this->loadModel('ElearningCategories');
		$category = $this->ElearningCategories->find('all')->where(['ElearningCategories.status'=> 1 ])->toArray();
		//pr($category); die;
		$Darray = array();
		$i=0;
		//echo "<pre>"; print_r($userservices); die;
		foreach($category as $val){
			$Darray[$i]['e_cat_Id'] 			= ($val->id) ? $val->id : '';
			$Darray[$i]['name'] 				= ($val->name) ? $val->name : '';
			$Darray[$i]['title'] 				= ($val->title) ? $val->title : '';
			$Darray[$i]['created'] 				= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
			
			$Darray[$i]['description'] 			= (@$val->description) ? @$val->description : '';
			$Darray[$i]['questions_image']		= (@$val->questions_image) ? $this->e_cat_imagePath.@$val->questions_image : '';
			$Darray[$i]['videos_image']			= (@$val->videos_image) ? $this->e_cat_imagePath.@$val->videos_image : '';
			$Darray[$i]['articles_image']		= (@$val->articles_image) ? $this->e_cat_imagePath.@$val->articles_image : '';
			$Darray[$i]['status']				= (@$val->status) ? @$val->status : '';
			$i++;
		}
		$response['status'] = "success";
		$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
		return  $response;
	}


	private function e_artical_list($e_category_id=NULL){
		$this->loadModel('User');
		$this->loadModel('ElearningArticles');
		@$ECID = ($e_category_id) ? $e_category_id : $this->request->getData['e_category_id'];
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		//echo $ECID; die;
		if($ECID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['ElearningArticles.id' => 'desc'],
				'page'=>$pageNo,
				//'contain' => ['Reviewtos','Reviewfroms']
			];
			$keyword = $this->request->query('keyword');
			$condition[] = ['ElearningArticles.category_id'=>$ECID,'ElearningArticles.status'=>1];
			$userservices = array();    
			$query = $this->ElearningArticles->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//echo "<pre>"; print_r($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['e_artical_id'] 	= ($val->id) ? $val->id : '';
				$Darray[$i]['category_id'] 		= ($val->category_id) ? $val->category_id : '';
				$Darray[$i]['title'] 			= ($val->title) ? $val->title : '';
				$Darray[$i]['created'] 			= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
				$Darray[$i]['content'] 			= (@$val->content) ? @$val->content : '';
				$Darray[$i]['image']			= (@$val->image) ? $this->e_artical_imagePath.@$val->image : '';
				$Darray[$i]['status'] 			= ($val->status) ? $val->status : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
				
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'e_category_id is required.';
		}
		return  $response;
	}

	private function e_video_list($e_category_id=NULL){
		$this->loadModel('User');
		$this->loadModel('ElearningVideos');
		@$ECID = ($e_category_id) ? $e_category_id : $this->request->getData['e_category_id'];
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		//echo $ECID; die;
		if($ECID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['ElearningVideos.id' => 'desc'],
				'page'=>$pageNo,
				//'contain' => ['Reviewtos','Reviewfroms']
			];
			$keyword = $this->request->query('keyword');
			$condition[] = ['ElearningVideos.category_id'=>$ECID,'ElearningVideos.status'=>1];
			$userservices = array();    
			$query = $this->ElearningVideos->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//echo "<pre>"; print_r($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['e_artical_id'] 	= ($val->id) ? $val->id : '';
				$Darray[$i]['category_id'] 		= ($val->category_id) ? $val->category_id : '';
				$Darray[$i]['title'] 			= ($val->title) ? $val->title : '';
				$Darray[$i]['created'] 			= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
				$Darray[$i]['description'] 		= (@$val->description) ? @$val->description : '';
				$Darray[$i]['url']				= (@$val->url) ? @$val->url : '';
				$Darray[$i]['status'] 			= ($val->status) ? $val->status : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
				
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'e_category_id is required.';
		}
		return  $response;
	}

	private function e_question_list($e_category_id=NULL){
		$this->loadModel('User');
		$this->loadModel('ElearningQuestions');
		@$ECID = ($e_category_id) ? $e_category_id : $this->request->getData['e_category_id'];
		@$pageNo = ($this->request->getData('pageNo')) ? $this->request->getData('pageNo') : 1;
		//echo $ECID; die;
		if($ECID !=''){
			$this->paginate = [
				'limit' => '10',//$limit,
				'order' => ['ElearningQuestions.id' => 'desc'],
				'page'=>$pageNo,
				//'contain' => ['Reviewtos','Reviewfroms']
			];
			$keyword = $this->request->query('keyword');
			$condition[] = ['ElearningQuestions.category_id'=>$ECID,'ElearningQuestions.status'=>1];
			$userservices = array();    
			$query = $this->ElearningQuestions->find('all')->where($condition);
			$totalCount =  $query->count();
			if($pageNo <= ceil($totalCount/10)){
				$userservices = $this->paginate($query);
				$userservices = $userservices->toArray();
			}
			$Darray = array();
			$i=0;
			//echo "<pre>"; print_r($userservices); die;
			foreach($userservices as $val){
				$Darray[$i]['e_artical_id'] 	= ($val->id) ? $val->id : '';
				$Darray[$i]['category_id'] 		= ($val->category_id) ? $val->category_id : '';
				$Darray[$i]['question_text'] 			= ($val->question_text) ? $val->question_text : '';
				$Darray[$i]['created'] 			= (@$val->created) ? date('Y-m-d H:i:s',strtotime(@$val->created)) : '';
				$Darray[$i]['option_a'] 			= (@$val->option_a) ? @$val->option_a : '';
				$Darray[$i]['option_b'] 			= (@$val->option_b) ? @$val->option_b : '';
				$Darray[$i]['option_c'] 			= (@$val->option_c) ? @$val->option_c : '';
				$Darray[$i]['option_d'] 			= (@$val->option_d) ? @$val->option_d : '';
				$Darray[$i]['correct_answer'] 		= (@$val->correct_answer) ? @$val->correct_answer : '';
				$Darray[$i]['status'] 			= ($val->status) ? $val->status : '';
				$i++;
			}
			$response['status'] = "success";
			$response['data'] = ($Darray) ? $this->array_filter_recursive($Darray) : array();
				
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'e_category_id is required.';
		}
		return  $response;
	}


	function resend(){
		$email = $this->request->getData('email');
		if($email !=''){
			//$this->loadModel('Users');
			
			$user = $this->Users->find()->where(['Users.email'=>$email])->first();
			 
			if(@$user->id !=''){
				$to=$email;
				//$to='raushan.kumar@evirtualservices.com';
				
				$sessionToken = $this->random_password(50);
				$verificationToken = $this->random_password(50);

				$user->verification_token = $verificationToken;
				$user->last_verification_email_sent = date('Y-m-d H:i:s');
				$this->Users->save($user);
				$this->request->session()->write('session_token', $sessionToken);
				//$verificationLink = Router::url(['controller' => 'Users', 'action' => 'verifyEmail', $verificationToken, $sessionToken], true);
				$verificationLink = Configure::read('App.siteurl') . 'users/verifyEmail/' . $verificationToken.'/'.$sessionToken;
				$subject="Welcome to RiteVet! Please verify your email.";
				$message="Dear ".ucfirst($user->firstName);
				$message .= "<br>Welcome to RiteVet! We're delighted to have you as a new member of our platform.";
				$message .= "<br>Please verify your email by clicking the link below:";
				$message .= "<br><br><a href='" . $verificationLink . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email</a>";
				$message .= "<br><br><strong>Note:</strong> Please open this link in the same browser to ensure proper verification.";
				//$this->phpemail($to, $subject, $message);
				$response['status'] = 'success';
				$response['msg'] = 'A new verification email has been sent to your email address.';
			}else{
				$response['status'] = 'Fails';
				$response['msg'] = 'No account found with that email address.';
			}
		}else{
			$response['status'] = 'Fails';
			$response['msg'] = 'email is required.';
		}
		return  $response;
	}

	function getCsrfToken(){
		$data = $this->set([
			'csrfToken' => $this->request->getAttribute('csrfToken'),
			'_serialize' => ['csrfToken']
		]);
		print_r($data); die;
	}

		
}