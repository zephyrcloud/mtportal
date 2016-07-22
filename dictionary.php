<?php

class dictionary{
	
	function words($id){
		$word = Array();
		/* index.php */
		$word[0] = "LogIn"; // button login 
		$word[1] = "Username and/or password incorrect"; // Message of error when there is a bad login.
		
		/*Users.php*/
		$word[2] = "User successfully created"; // message insert user
		$word[3] = "Failed action"; // If the action of insert or update is wrong
		$word[4] = "User successfully updated"; // Message of update user
		$word[5] = "User successfully deleted"; // message of deleted user
		$word[6] = 'Listado de apps fallido: '; // if the app list has had a error
		$word[7] = 'Creación de la asignación fallida: '; //API assign fail
		$word[8] = 'Remover la asignación fallida: '; // Remove a app
		$word[9] = "Apps successfully assigned"; // App created
		$word[10] = "Emails successfully updated"; // update emails
		$word[11] = "Users"; // Tittles of the page
		$word[12] = "Consulta fallida: "; //fail queries
		$word[13] = "First Name"; // title of the first column on add user,edit user,table
		$word[14] = "Last Name"; // title of the second column on add user,edit user,table
		$word[15] = "Default Email"; // title of the third column on add user,edit user
		$word[16] = "Outbound DID"; // title of the fourth colunm on add user,edit user,table
		$word[17] = "Extension"; // title of the fifth colunm on add user,edit user,table
		$word[18] = "Actions"; // column for actions
		$word[19] = "Edit"; // subcolumn for actions
		$word[20] = "Emails"; // subcolumn for actions
		$word[21] = "Apps"; // subcolumn for actions
		$word[22] = "Delete"; // subcolumn for actions
		$word[23] = "Add user"; // button
		
		/*domaincustomers.php*/
		$word[24] = "Domains"; // tittle
		$word[25] = "Lookup Domain"; // Text field tittle
		$word[26] = "You over the quota and do not have permited to regitrer more domains, please contact the administrator"; // Message of over the permited quotas
		$word[27] = "Click here for going to the screen profile"; // Link text for going to screen profile
		$word[28] = "This domain has been taken , please try other domain of this list or another you choose."; // Suggest domain when one isn't available 
		$word[29] = "Domain"; // column table when suggest to the user others domains
		$word[30] = "Status"; // column table when suggest to the user others domains
		$word[31] = "Action"; // column table when suggest to the user others domains
		$word[32] = "This domain has been taken and you over the quotas permited to regitrer more domains"; // message when the qouta is 0
		$word[33] = "You over the quota and do not have permited to regitrer more domains, please contact the administrator"; // When the user doesn't have quotas and use lookup domain
		$word[34] = "it was a mistake with the information."; // If something happens with the register of domain.
		
		/*registrerDomain.php*/
		$word[35] = "Click here for cancel order"; // Link text for go back
		$word[36] = "Retrieve Order information"; // Title of table of retreive 
		$word[37] = "Lookfor domains"; // Field of description 
		$word[38] = "Previous Domain"; // Field of retreive data
		$word[39] = "Username"; // Field of retreive data
		$word[40] = "Password"; // Field of retreive data
		$word[41] = "retrieve data"; // Button Field of retreive data
		
		$word[42] = "Domain information"; // Title of table for registration
		$word[43] = "Domain Name"; 
		$word[44] = "Registration Type";
		$word[45] = "Confirm Password";		
		$word[46] = "Click here for retreive data "; // Link text for cancel orden
		
		$word[47] = "Owner Contact Information"; // Owner contact
		$word[48] = "First Name "; //fields
		$word[49] = "Last Name "; //fields
		$word[50] = "Organization Name "; //fields
		$word[51] = "Street Address "; //fields
		$word[52] = "(eg: Suite #245) [optional] "; //fields
		$word[53] = "Address 3 [optional] "; //fields
		$word[54] = "City "; //fields
		$word[55] = "State "; //fields
		$word[56] = "2 Letter Country Code "; //fields
		$word[57] = "Postal Code "; //fields
		$word[58] = "Phone Number "; //fields
		$word[59] = "Fax Number[optional] "; //fields
		$word[60] = "Email "; //fields
		
		$word[61] = "Admin Contact Information"; // Admin contact
		$word[62] = "Billing Contact Information"; // Billing contact
		$word[63] = "Technical Contact Information"; // Technical contact
		
		$word[64] = "Same As Owner Contact Information"; 
		$word[65] = "Same As Admin Contact Information"; 
		$word[66] = "Same As Billing Contact Information";
		$word[67] = "Submit"; // button that make the action to register a domain.
		
		/*reportsphp*/
		
		$word[68] = "Reports"; // Tittle of the section and slide
		$word[69] = "Archive reports"; 
		$word[70] = "Filter by";  // first combobox on reports
		$word[71] = "Select a option";  // first option of the comboboxes
		
		$word[72] = "User";  // Options of the firs combobox ($word[70])
		$word[73] = "Action type";  // Options of the firs combobox ($word[70])
		$word[74] = "Table modified";  // Options of the firs combobox ($word[70])
		$word[75] = "Result";  // Options of the firs combobox ($word[70])
		$word[76] = "Especific day";  // Options of the firs combobox ($word[70])
		
		$word[77] = "Filter by parameter";  // sub combobox of $word[70]
		$word[78] = "Apply";  // sub button of sub combobox of $word[70]
		
		$word[79] = "Archive Logs";  // Button for history logs
		
		$word[80] = "Time";  // Column for reports an archive reports
		$word[81] = "Ip Address";  // Column for reports an archive reports
		$word[82] = "User";  // Column for reports an archive reports
		$word[83] = "Action Type";  // Column for reports an archive reports
		$word[84] = "Table modified";  // Column for reports an archive reports
		$word[85] = "Result";  // Column for reports an archive reports
		
		/*intoDomain.php*/
		$word[86] = "Renew this domain";  
		$word[87] = "Are you sure that you want to renew this domain ?";  
		$word[88] = "Authentication Error in transfer domain, please verify the information."; //if there is a mistake , report this message
		
		return $word[$id];
	}
	
}

?>