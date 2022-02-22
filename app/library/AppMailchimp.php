<?php
class appMailchimp {
	protected $m_ci;
	protected $m_User;
	protected $m_user_id;
	protected $m_category_id;
	protected $m_mailchimp_api_key;
	protected $m_timeout;
	protected $m_base_url;
	protected $m_default_folders_count= 9999;

	public function __construct() {
		$this->m_ci = &get_instance();
		$this->m_ci->load->model('mcategory', '', true);
		$this->m_timeout= 10;

		$this->m_mailchimp_api_key= !empty($this->m_ci->m_appConfig['mailchimp_api_key']) ? $this->m_ci->m_appConfig['mailchimp_api_key'] : '';

		$mailchimp_center = substr( $this->m_mailchimp_api_key, strpos( $this->m_mailchimp_api_key, '-' ) + 1 );
		$this->m_base_url= 'https://' . $mailchimp_center . '.api.mailchimp.com/3.0/';
	}

	public function setMailchimpApiKey( string $p_mailchimp_api_key ) {
		$this->m_mailchimp_api_key= $p_mailchimp_api_key;
	}

	public function setTimeout( int $p_timeout ) {
		$this->m_timeout= $p_timeout;
	}

	public function setDefaultFoldersCount( int $p_default_folders_count ) {
		$this->m_default_folders_count= $p_default_folders_count;
	}

	public function setUserId( int $p_user_id ) {
		$this->m_user_id= $p_user_id;
		$this->m_User= $this->muser->getRowById($p_user_id);
	}


	public function setUser( array $p_User ) {
		$this->m_User= $p_User;
		$this->m_user_id= $p_User['id'];
	}

	public function setCategoryId( int $p_category_id ) {
		$this->m_category_id= $p_category_id;
	}

	public function subscribeCategory( string $set_status ) : array {
		$validatedErrors= $this->hasValidatedErrors( [ 'user_email'=>true, 'category_id'=> true ] );
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$Category = $validatedErrors['Category'];

		$mailchimp_list_id   = $Category['mailchimp_list_id'];
		$mailchimp_list_name = $Category['name'];

		$mailchimp_user_id         = md5( strtolower( $this->m_User['email'] ) );
		$url = $this->m_base_url .'lists/' . $mailchimp_list_id . '/members/' . $mailchimp_user_id ;

		$userInfo = json_encode( [ // member information
			'email_address' => $this->m_User['email'],
			'status'        => $set_status,  // "subscribed" or "unsubscribed" or "cleaned" or "pending"
			'merge_fields'  => [
				'FNAME' => $this->m_User['first_name'],
				'LNAME' => $this->m_User['last_name']
			]
		] );

		$ch= $this->initCurl($url,'PUT');
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $userInfo );
		$curlReturnedData   = curl_exec( $ch );
		$resultArray= json_decode($curlReturnedData);
		$error_details= ( !empty($resultArray->status) ? ' status:'.$resultArray->status.', ' : '') .
		                ( !empty($resultArray->title) ? ' title:'.$resultArray->title.', ' : '') .
		                ( !empty($resultArray->detail) ? ' detail:'.$resultArray->detail.', ' : '');
		$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) {  there are errors

		if ( $http_code == 200 ) {
			return['error_code'=> 0, 'error_message' => "", 'success_message' => "You have successfully ".$set_status." to '" . $this->m_ci->m_appConfig['site_name'] . ", list '" . $mailchimp_list_name."' !" ];
		} else {
			switch ( $http_code ) {
				case 214:
					return [ 'error_code' => $http_code, '214:error_message' => " Error with code " . $http_code . $error_details . ' ! Check api key and List ID !' ];
				default:
					return [ 'error_code' => $http_code, $http_code.':error_message' => " Error with code " . $http_code . $error_details . ' ! Check api key and List ID !' ];
			}
		}

		return [ 'error_code' => 99, '99:error_message' => " Unknown Error ! ".$error_details." Check api key and List ID !" ];
	} // public function subscribeCategory( $set_status ) : array { // subscribe/unsubscribing Category/List


	/* MAILCHIMP LISTS API BLOCK START */

	public function getListsInfo() : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;

		$url = $this->m_base_url .'lists';
		$ch= $this->initCurl($url,'GET');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
		$retArray= [];
		if( !empty($curlReturnedData->lists) ) {
			foreach( $curlReturnedData->lists as $list ){
				$retArray[]= ['list_id'=>$list->id, 'list_name'=> $list->name, 'member_count'=> $list->stats->member_count, 'date_created'=> $list->date_created];
			}
		}
		return $retArray;
	}  // 	public function getListsInfo(  ) : array {

	public function addListItem( array $listItemInfo ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'lists';
		$ch= $this->initCurl($url,'POST');
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $listItemInfo ) );
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		curl_close( $ch );

		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
		return['error_code'=> 0, 'error_message' => "", 'new_list_item_id' => ( !empty($curlReturnedData->id) ? $curlReturnedData->id : false ) ];
	}  // public function addListItem( $list_item= '' ) : array { // add List Item

	public function deleteList( string $list_id ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'lists/'.$list_id;
		$ch= $this->initCurl($url,'DELETE');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );

		if ( !empty($curlReturnedData->status)   ) { // there are errors
			$errors_text= ( !empty($curlReturnedData->title) ? $curlReturnedData->title.", " : "" ) . ( !empty($curlReturnedData->detail) ? $curlReturnedData->detail.", " : "" );
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
		return['error_code'=> 0, 'error_message' => "", 'list_id' => $list_id ];
	}  // public function deleteList( $list_id ) :


	public function getListMembers(string $list_id) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;

		$url = $this->m_base_url .'lists/'.$list_id.'/members'; // /lists/{list_id}/members
		$ch= $this->initCurl($url,'GET');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
//		echo '<pre>$curlReturnedData::'.print_r($curlReturnedData,true).'</pre>';
		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
		$retArray= [];
		if( !empty($curlReturnedData->members) ) {
			foreach( $curlReturnedData->members as $member ) {
				$retArray[]= ['member_id'=>$member->id, 'email'=> $member->email_address, 'status'=> $member->status,
				              'username'=> ( !empty($member->merge_fields->FNAME) ? $member->merge_fields->FNAME : '' ) . ' ' .
				                           ( !empty($member->merge_fields->LNAME) ? $member->merge_fields->LNAME : '' )
				];
			}
		}
		return $retArray;
	}  // 	public function getListMembers($list_id):array {

	/* MAILCHIMP LISTS API BLOCK END */



	/* MAILCHIMP TEMPLATES API BLOCK START */
	public function getTemplateFoldersList() : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;

		$parametersList= [];
		if ( !empty($this->m_default_folders_count) ) {
			$parametersList['count'] = $this->m_default_folders_count;
		}
		$url = $this->m_base_url .'template-folders'.$this->setParametersString($parametersList, true);

		$ch= $this->initCurl($url,'GET');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		}  //if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) {  there are errors
		$retArray= [];
		if( !empty($curlReturnedData->folders) ) {
			foreach( $curlReturnedData->folders as $folder ){
				$retArray[]= ['id'=>$folder->id, 'name'=> $folder->name, 'count'=> $folder->count];
			}
		}
		return $retArray;
	}  // 	public function getTemplateFoldersList( $set_status= '' ) : array { // get Template Folders info/list


	public function getTemplateList(bool $filter_user= true) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;

		$parametersList= [];
		$url = $this->m_base_url .'templates'.$this->setParametersString($parametersList, true);
		$ch= $this->initCurl($url,'GET');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		}  //if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) {  there are errors
		$retArray= [];

			appUtils::debToFile( '  getTemplateList  $curlReturnedData->templates::' . print_r($curlReturnedData->templates, true), false);
		if( !empty($curlReturnedData->templates) ) {
			foreach( $curlReturnedData->templates as $folder ){
				if ( $filter_user and $folder->type != 'user' ) continue;
				$retArray[]= [ 'template_id'=>$folder->id, 'template_name'=> $folder->name, 'type'=> $folder->type , 'category'=> $folder->category , 'date_created'=> $folder->date_created , 'date_created_formatted'=> appUtils::formatDateTime(strtotime($folder->date_created), 'asText') ]; // appUtils::formatDateTime(strtotime($nextFile->created_at), 'asText')
			}
		}
		return $retArray;
	}  // 	public function getTemplateFoldersList( $set_status= '' ) : array { // get Template Folders info/list

	// $newMailChimpTemplate=$appMailchimp->addTemplate($new_mailchimp_template, $mailchimp_template_folder_id
	public function addTemplate( string $template_name, int $mailchimp_template_folder_id, string $content ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'templates' ;

		$templateInfo  = [
			'name' => $template_name,
			'folder_id'=> $mailchimp_template_folder_id,
			"html" => $content
		];

		$ch= $this->initCurl($url,'POST');
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $templateInfo ) );
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		curl_close( $ch );
		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors

		return['error_code'=> 0, 'error_message' => "", 'new_template_id' => ( !empty($curlReturnedData->id) ? $curlReturnedData->id : false ) ];
	}  // public function addTemplate( $template_name ) : array { // add Template

	public function deleteTemplate(int $template_id) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'templates/'.$template_id;
		$ch= $this->initCurl($url,'DELETE');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );

		if ( !empty($curlReturnedData->status)   ) { // there are errors
			$errors_text= ( !empty($curlReturnedData->title) ? $curlReturnedData->title.", " : "" ) . ( !empty($curlReturnedData->detail) ? $curlReturnedData->detail.", " : "" );
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
		return['error_code'=> 0, 'error_message' => "", 'template_id' => $template_id ];
	}  // public function deleteTemplate( $template_id ) :

	/* MAILCHIMP TEMPLATES API BLOCK END */



	/* MAILCHIMP FOLDERS API BLOCK START */
	public function getFoldersList() : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;

		$parametersList= [];
		if ( !empty($this->m_default_folders_count) ) {
			$parametersList['count'] = $this->m_default_folders_count;
		}
		$url = $this->m_base_url .'file-manager/folders'.$this->setParametersString($parametersList, true);

		$ch= $this->initCurl($url,'GET');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		}  //if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) {  there are errors
		$retArray= [];
		if( !empty($curlReturnedData->folders) ) {
			foreach( $curlReturnedData->folders as $folder ){
				$retArray[]= ['id'=>$folder->id, 'name'=> $folder->name, 'file_count'=> $folder->file_count, 'created_at'=> $folder->created_at];
			}
		}
		return $retArray;
	}  // 	public function getFoldersList( $set_status= '' ) : array { // get Lists info



	public function addFolder( string $folder_name ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'file-manager/folders' ;

		$folderInfo  = [
			'name' => $folder_name,
		];

		$ch= $this->initCurl($url,'POST');
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $folderInfo ) );
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		curl_close( $ch );

		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors

		return['error_code'=> 0, 'error_message' => "", 'new_folder_id' => ( !empty($curlReturnedData->id) ? $curlReturnedData->id : false ) ];
	}  // public function addFolder( $folder_name ) : array { // add Folder

	/* MAILCHIMP FOLDERS API BLOCK START */


	public function deleteFolder(int $folder_id ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'file-manager/folders/'.$folder_id ;
		$ch= $this->initCurl($url,'DELETE');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		if ( !empty($curlReturnedData->status)   ) { // there are errors
			$errors_text= ( !empty($curlReturnedData->title) ? $curlReturnedData->title.", " : "" ) . ( !empty($curlReturnedData->detail) ? $curlReturnedData->detail.", " : "" );
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors

		return['error_code'=> 0, 'error_message' => "", 'folder_id' => $folder_id ];
	}  // public function deleteFolder( $folder_id ) :
	/* MAILCHIMP FOLDERS API BLOCK START */


	/* MAILCHIMP FILES API BLOCK START */
	public function getFilesList(bool $get_folder_name= false, int $folder_id= 0) : array { // get Lists info
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;

		$url = $this->m_base_url .'file-manager/files' ;

		$ch= $this->initCurl($url,'GET', 'getFilesList ');
		$result   = json_decode( curl_exec( $ch ) );
		$retArray= [];
		$foldersList= [];
		if ( $get_folder_name ) {
			$foldersList= $this->getFoldersList();
		}
//		echo '<pre>$folder_id::'.print_r($folder_id,true).'</pre>';
		if( !empty($result->files) ) {
			foreach( $result->files as $nextFile ){
//				echo '<pre>$nextFile::'.print_r($nextFile,true).'</pre>';
				$folder_name= '';
				if ( $get_folder_name ) {
					foreach( $foldersList as $nextFolder ) {
						if ( (int)$nextFolder['id'] == (int)$nextFile->folder_id) {
							$folder_name= $nextFolder['name'];
							break;
						}
					}
				}
				if ( !empty($folder_id) and (int)$nextFile->folder_id != (int)$folder_id )continue;
				$retArray[]= [ 'id'=>$nextFile->id, 'folder_id'=>$nextFile->folder_id, 'folder_name'=>$folder_name, 'name'=>$nextFile->name, 'type'=>$nextFile->type, 'full_size_url'=> $nextFile->full_size_url, 'thumbnail_url'=> $nextFile->thumbnail_url, 'size'=> $nextFile->size, 'size_label'=> appUtils::getFileSizeAsString($nextFile->size), 'created_at_formatted'=> appUtils::formatDateTime(strtotime($nextFile->created_at), 'asText'), 'created_at'=> $nextFile->created_at, 'width'=> $nextFile->width, 'height'=> $nextFile->height ];
			}
		}
		return $retArray;
	}  // 	public function getFilesList( $set_status= '' ) : array { // get Lists info


	public function addFile( string $filename, string $file_data, int $mailchimp_folder_id= 0 ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'file-manager/files' ;

		$fileInfo  = [
			'name' => $filename,
			'folder_id'=> (int)$mailchimp_folder_id,
			'file_data'=> base64_encode($file_data)
		];

		$ch= $this->initCurl($url,'POST', 'addFile ');
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fileInfo ) );
		$curlReturnedData   = json_decode( curl_exec( $ch ) );
		curl_close( $ch );

		if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors
			$errors_text= $this->getErrorMessageText($curlReturnedData);
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		}  //if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) {  there are errors
		return['error_code'=> 0, 'error_message' => "", 'new_file_id' => ( !empty($curlReturnedData->id) ? $curlReturnedData->id : false ) ];
	}  // public function addTemplate( $filename ) : array { // add File


	public function updateFile( int $file_id, $filename, string $filename_content, int $mailchimp_folder_id= 0 ) : array {

		$deletedMailChimpFile=$this->deleteFile($file_id);
		if ( !empty($deletedMailChimpFile['error_code']) ) return $deletedMailChimpFile;

		$newMailChimpFile= $this->addFile($filename, $filename_content, $mailchimp_folder_id);
		if ( !empty($newMailChimpFile['error_code']) ) {
			return array( 'error_message' => $newMailChimpFile['error_message'], 'error_code' => $newMailChimpFile['error_code'], 'file_id'=> '' ) ;
		}

		return array( 'error_message' => '', 'error_code' => 0, 'file_id'=> $newMailChimpFile['new_file_id'] );
	}  // public function updateFile( int $file_id ) :


	public function deleteFile( int $file_id ) : array {
		$validatedErrors= $this->hasValidatedErrors();
		if( $validatedErrors['error_code'] ) return $validatedErrors;
		$url = $this->m_base_url .'file-manager/files/'.$file_id;
		$ch= $this->initCurl($url,'DELETE');
		$curlReturnedData   = json_decode( curl_exec( $ch ) );

		if ( !empty($curlReturnedData->status)   ) { // there are errors
			$errors_text= ( !empty($curlReturnedData->title) ? $curlReturnedData->title.", " : "" ) . ( !empty($curlReturnedData->detail) ? $curlReturnedData->detail.", " : "" );
			return [ 'error_code' => ( !empty($curlReturnedData->status) ? $curlReturnedData->status : 1 ), 'error_message' => appUtils::trimRightSubString($errors_text,', ') ];
		} // if ( !empty($curlReturnedData->errors) and is_array($curlReturnedData->errors) ) { // there are errors

		return['error_code'=> 0, 'error_message' => "", 'file_id' => $file_id ];
	}  // public function deleteFile( $file_id ) :

	/* MAILCHIMP FILES API BLOCK START */


	private function hasCurlOn() : bool {
		return function_exists('curl_version');
	}

	private function getErrorMessageText($returnData) : string {
		$errors_text= '';
		if ( !empty($returnData->title) ) {
			$errors_text.= 'title : '.$returnData->title.', ';
		}
		if ( !empty($returnData->status) ) {
			$errors_text.= 'status : '.$returnData->status.', ';
		}
		if ( !empty($returnData->detail) ) {
			$errors_text.= 'detail : '.$returnData->detail.', ';
		}
		$errors_text= appUtils::trimRightSubString($errors_text,', ');
		$errors_text.= '   Errors : ';
		foreach( $returnData->errors as $nextError ) {
			if ( !empty($nextError->message) ) {
				$errors_text.= $nextError->field.' : '.$nextError->message.', ';
			}
		}
		$errors_text= appUtils::trimRightSubString($errors_text,', ');
		return $errors_text;
	}

	private function setParametersString(array $parametersList, bool $is_first_parameter= false) : string {
		if ( empty($parametersList) or !is_array($parametersList)) return '';
		$ret_str= '';
		$is_first_in_circle= true;
		foreach( $parametersList as $next_key=>$next_value ) {
		    if ( $is_first_in_circle ) {
			    $ret_str.= ( $is_first_parameter ? '?' : '&' ) . $next_key.'='.$next_value;
		    } else {
			    $ret_str.= '&' . $next_key.'='.$next_value;
		    }
		}
		return $ret_str;
	}

	private function initCurl(string $url, string $request_type, string $src= '') {

//		if ( !empty($src) ) {
//			appUtils::debToFile($src.'  $url::' . print_r($url, true), false);
//		}
		$ch = curl_init( trim($url) );
		curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $this->m_mailchimp_api_key );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $this->m_timeout );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $request_type );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
//		appUtils::debToFile(' initCurl $ch::' . print_r($ch, true), false);
		return $ch;
	}

	public function hasValidatedErrors( array $makeChecks = [] ) : array {
		if ( empty( $this->m_mailchimp_api_key ) ) {
			return [ 'error_code' => 2, 'error_message' => " 2:mailchimp api key is not set !" ];
		}
		if ( ! $this->hasCurlOn() ) {
			return [ 'error_code' => 3, 'error_message' => "3:Curl is not enabled !" ];
		}
		if ( in_array('user_email', $makeChecks) ) {
			if ( empty( $this->m_User['email'] ) or ! filter_var( $this->m_User['email'], FILTER_VALIDATE_EMAIL ) ) {
				return [ 'error_code' => 1, 'error_message' => "1:Invalid email '" . $this->m_User['email'] . "' " ];
			}
		}

		$Category= '';
		if ( in_array('category_id', $makeChecks) ) {
			if ( empty( $this->m_category_id ) or ! is_numeric( $this->m_category_id ) ) {
				return [ 'error_code' => 4, 'error_message' => "4:Category Id is not set !" ];
			}
			$Category = $this->m_ci->mcategory->getRowById( $this->m_category_id );
			if ( empty( $Category ) ) {
				return [ 'error_code' => 7, 'error_message' => "7:Invalid Category ID '" . $this->m_category_id . "' given !" ];
			}
			if ( empty( $Category['mailchimp_list_id'] ) ) {
				return [ 'error_code' => 8, 'error_message' => "8:For given category '" . $this->m_category_id . "' mailchimp list id is not set !" ];
			}
		}
		return [ 'error_code' => 0, 'error_message' => "", 'Category'=> $Category ];
	} // public function preValidate( $makeChecks ) : array {
}
