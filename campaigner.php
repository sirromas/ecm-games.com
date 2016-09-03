<?php

function create_campaigner_list($list_name) {
    $soap_request = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
			<CreateUpdateContactGroups xmlns="https://ws.campaigner.com/2013/01">
			  <authentication>
				<Username>' . CAMPAIGNER_EMAIL . '</Username>
				<Password>' . CAMPAIGNER_PASSWORD . '</Password>
			  </authentication>
			  <contactGroupType>MailingList</contactGroupType>
			  <contactGroupId>0</contactGroupId>
			  <name>' . $list_name . '</name>
			  <description>' . $list_name . '</description>			  
			  <isGroupVisible>true</isGroupVisible>
			  <isTempGroup>false</isTempGroup>
			</CreateUpdateContactGroups>
		  </soap:Body>
		</soap:Envelope>';


    $header = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Cache-Control: no-cache",
        "SOAPAction: \"https://ws.campaigner.com/2013/01/CreateUpdateContactGroups\"",
        "Content-length: " . strlen($soap_request),
    );

    $soap_do = curl_init();
    curl_setopt($soap_do, CURLOPT_URL, "https://ws.campaigner.com/2013/01/listmanagement.asmx");
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($soap_do, CURLOPT_POST, true);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $soap_request);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($soap_do);

    if ($result === false) {
        $err = 'Curl error: ' . curl_error($soap_do);
        curl_close($soap_do);
        //print $err;
    } else {
        //header("Content-type: text/xml");
        //echo $result; 			
        /* echo '<pre>';			print_r($result1['soap:Envelope']['soap:Body']['ListContactGroupsResponse']['ListContactGroupsResult']['ContactGroupDescription']);			
          //print_r($result1);
          echo '</pre>'; */
        curl_close($soap_do);
        $result1 = xml2array_new($result);
        /* echo '<pre>';
          print_r($result1);
          echo '</pre>'; */
        return $result1['soap:Envelope']['soap:Body']['CreateUpdateContactGroupsResponse']['CreateUpdateContactGroupsResult']['ContactGroupId'];
    }
}

function list_campaigner_list() {
    $soap_request = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
			<ListContactGroups xmlns="https://ws.campaigner.com/2013/01">
			  <authentication>
				<Username>' . CAMPAIGNER_EMAIL . '</Username>
				<Password>' . CAMPAIGNER_PASSWORD . '</Password>
			  </authentication>
			</ListContactGroups>
		  </soap:Body>
		</soap:Envelope>';


    $header = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Cache-Control: no-cache",
        "SOAPAction: \"https://ws.campaigner.com/2013/01/ListContactGroups\"",
        "Content-length: " . strlen($soap_request),
    );

    $soap_do = curl_init();
    curl_setopt($soap_do, CURLOPT_URL, "https://ws.campaigner.com/2013/01/listmanagement.asmx");
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($soap_do, CURLOPT_POST, true);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $soap_request);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($soap_do);

    if ($result === false) {
        $err = 'Curl error: ' . curl_error($soap_do);
        curl_close($soap_do);
        //print $err;
    } else {
        //header("Content-type: text/xml");
        //echo $result; 			
        /* echo '<pre>';			print_r($result1['soap:Envelope']['soap:Body']['ListContactGroupsResponse']['ListContactGroupsResult']['ContactGroupDescription']);			
          //print_r($result1);
          echo '</pre>'; */
        curl_close($soap_do);
        $result1 = xml2array_new($result);
        return $result1['soap:Envelope']['soap:Body']['ListContactGroupsResponse']['ListContactGroupsResult']['ContactGroupDescription'];
    }
}

function xml2array_new($contents, $get_attributes = 1, $priority = 'tag') {
    if (!$contents)
        return array();

    if (!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }


    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return; //Hmm...	

        
//Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference
    //Go through the tags.
    $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
    foreach ($xml_values as $data) {
        unset($attributes, $value); //Remove existing values, or there will be trouble
        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data); //We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();

        if (isset($value)) {
            if ($priority == 'tag')
                $result = $value;
            else {
                if ($current[$tag][0]) {
                    $result['value'] = $value;
                } else {
                    $result[0]['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
                }
            }
        }

        //Set the attributes too.
        if (isset($attributes) and $get_attributes) {
            foreach ($attributes as $attr => $val) {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else {
                    if ($current[$tag][0]) {
                        $result['attr'][$attr] = $val;
                    } else {
                        $result[0]['attr'][$attr] = $val; //Put the value in a assoc array if we are in the 'Attribute' mode
                    }
                }
                //else $result[0]['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if ($type == "open") {//The starting of the tag '<tag>'
            $parent[$level - 1] = &$current;
            if (!is_array($current) or ( !in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;

                $current = &$current[$tag];
            } else { //There was another element with the same tag name
                if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level] ++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag . '_' . $level] = 2;

                    if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = &$current[$tag][$last_item_index];
            }
        } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if (!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            } else { //If taken, put all things inside a list(array)
                if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                    if ($priority == 'tag' and $get_attributes and $attributes_data) {

                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level] ++;
                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag], $result);
                    //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes) {
                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }

                        if ($attributes_data) {

                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level] ++; //0 and 1 index is already taken
                }
            }
        } elseif ($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level - 1];
        }
    }

    return($xml_array);
}

function add_to_campaigner($email_address, $list_id, $first_name = '', $last_name = '') {
    $soap_request = '<?xml version="1.0" encoding="utf-8"?>
			<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>
			<ImmediateUpload xmlns="https://ws.campaigner.com/2013/01">
			<authentication>
			  <Username>' . CAMPAIGNER_EMAIL . '</Username>
			  <Password>' . CAMPAIGNER_PASSWORD . '</Password>
			</authentication>
			<UpdateExistingContacts>false</UpdateExistingContacts>
			<TriggerWorkflow>true</TriggerWorkflow>
			<contacts>
			  <ContactData>
				<ContactKey>
				  <ContactId>0</ContactId>
				  <ContactUniqueIdentifier>' . $email_address . '</ContactUniqueIdentifier>
				</ContactKey>
				<EmailAddress>' . $email_address . '</EmailAddress>
				<FirstName>' . $first_name . '</FirstName>
				<LastName>' . $last_name . '</LastName>   
				<IsTestContact>false</IsTestContact>    
				<AddToGroup>
				  <int>' . $list_id . '</int>      
				</AddToGroup>   
			  </ContactData>  
			</contacts>
		   </ImmediateUpload>	
		  </soap:Body>
		</soap:Envelope>';

    $header = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Cache-Control: no-cache",
        "SOAPAction: \"https://ws.campaigner.com/2013/01/ImmediateUpload\"",
        "Content-length: " . strlen($soap_request),
    );

    $soap_do = curl_init();
    curl_setopt($soap_do, CURLOPT_URL, "https://ws.campaigner.com/2013/01/contactmanagement.asmx");
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($soap_do, CURLOPT_POST, true);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $soap_request);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($soap_do);

    if ($result === false) {
        $err = 'Curl error: ' . curl_error($soap_do);
        curl_close($soap_do);
        //print $err;
    } else {

        curl_close($soap_do);
        $result1 = xml2array_new($result);
        print_r($result1);
        return $result1['soap:Envelope']['soap:Body']['ImmediateUploadResponse']['ImmediateUploadResult']['UploadResultData']['ResultCode'];
    }
}

function request_campaigner_unsubscribed() {
    $soap_request = '<?xml version="1.0" encoding="utf-8"?>
			<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>			
			<RunReport xmlns="https://ws.campaigner.com/2013/01">
				<authentication>
				  <Username>' . CAMPAIGNER_EMAIL . '</Username>
				  <Password>' . CAMPAIGNER_PASSWORD . '</Password>
				</authentication>
				<xmlContactQuery><![CDATA[<contactssearchcriteria><version major="2" minor="0" build="0" revision="0" /><set>Partial</set><evaluatedefault>True</evaluatedefault><group><filter><filtertype>SearchAttributeValue</filtertype><systemattributeid>2</systemattributeid><action><type>DDMMYY</type><operator>WithinLastNDays</operator><value>5</value></action></filter><filter><relation>Or</relation><filtertype>SearchAttributeValue</filtertype><systemattributeid>3</systemattributeid><action><type>DDMMYY</type><operator>WithinLastNDays</operator><value>5</value></action></filter></group><group><relation>And</relation><filter><filtertype>SearchAttributeValue</filtertype><systemattributeid>1</systemattributeid><action><type>Numeric</type><operator>EqualTo</operator><value>3</value></action></filter><filter><relation>Or</relation><filtertype>SearchAttributeValue</filtertype><systemattributeid>1</systemattributeid><action><type>Numeric</type><operator>EqualTo</operator><value>1</value></action></filter></group></contactssearchcriteria>]]></xmlContactQuery>		 							
			</RunReport>
		  </soap:Body>
		</soap:Envelope>';

    $header = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Cache-Control: no-cache",
        "SOAPAction: \"https://ws.campaigner.com/2013/01/RunReport\"",
        "Content-length: " . strlen($soap_request),
    );

    $soap_do = curl_init();
    curl_setopt($soap_do, CURLOPT_URL, "https://ws.campaigner.com/2013/01/contactmanagement.asmx");
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($soap_do, CURLOPT_POST, true);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $soap_request);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($soap_do);

    if ($result === false) {
        $err = 'Curl error: ' . curl_error($soap_do);
        curl_close($soap_do);
        //print $err;
    } else {
        header("Content-type: text/xml");
        echo $result;
        /* echo '<pre>';			print_r($result1['soap:Envelope']['soap:Body']['ListContactGroupsResponse']['ListContactGroupsResult']['ContactGroupDescription']);			
          //print_r($result1);
          echo '</pre>'; */
        curl_close($soap_do);
        /* echo '<pre>';	
          print_r($result);
          echo '</pre>'; */
    }
}

function download_campaigner_unsubscribed($ReportTicketId, $RowCount = '') {
    $soap_request = '<?xml version="1.0" encoding="utf-8"?>
			<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>			
			<DownloadReport xmlns="https://ws.campaigner.com/2013/01/">
				<authentication>
				  <Username>' . CAMPAIGNER_EMAIL . '</Username>
				  <Password>' . CAMPAIGNER_PASSWORD . '</Password>
				</authentication>
				<reportTicketId>' . $ReportTicketId . '</reportTicketId> 
				<fromRow>1</fromRow> 
				<toRow>5</toRow> 				 							
			</DownloadReport>
		  </soap:Body>
		</soap:Envelope>';

    $header = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Cache-Control: no-cache",
        "SOAPAction: \"https://ws.campaigner.com/2013/01/DownloadReport\"",
        "Content-length: " . strlen($soap_request),
    );

    $soap_do = curl_init();
    curl_setopt($soap_do, CURLOPT_URL, "https://ws.campaigner.com/2013/01/contactmanagement.asmx");
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($soap_do, CURLOPT_POST, true);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $soap_request);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($soap_do);

    if ($result === false) {
        $err = 'Curl error: ' . curl_error($soap_do);
        curl_close($soap_do);
        //print $err;
    } else {
        header("Content-type: text/xml");
        echo $result;
        /* echo '<pre>';			print_r($result1['soap:Envelope']['soap:Body']['ListContactGroupsResponse']['ListContactGroupsResult']['ContactGroupDescription']);			
          //print_r($result1);
          echo '</pre>'; */
        curl_close($soap_do);
        /* echo '<pre>';	
          print_r($result);
          echo '</pre>'; */
    }
}

//request_campaigner_unsubscribed();
//download_campaigner_unsubscribed('ACB0AE0D-BE36-4EA7-9B48-725AD7E0C7B5',$RowCount='')
//List fetching
/* $arr_list	=	list_campaigner_list();
  if(is_array($arr_list) && !empty($arr_list))
  {
  echo '<select name="select">';
  foreach($arr_list as $values)
  {
  echo '<option value="'.$values['Id'].'">'.$values['Name'].'</option>';
  }
  echo '</select>';
  } */

//Contact Add
/*
  $email_address	=	'test@test.com';
  $list_id		=	'3341394';
  $first_name		=	'testfname';
  $last_name		=	'testlname';

  add_to_campaigner($email_address,$list_id,$first_name,$last_name);
 */
?>
