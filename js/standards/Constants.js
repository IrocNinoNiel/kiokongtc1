/** Overrides
  * [Developer]
  * In Memory: Salrio T. Salcedo
  * Date Created: January 2016
  
  * [Description]
	Stores all JS global variables and standard settings
  
  * [Modification]
    Almost each and every single day :D
	Except when Im gone :(
 **/

var constants = function(){
	
	function constantVar( params ){
		var config = {
			/** Messages **/
			MSGBOX_TITLE  			: 'SYSTEM MESSAGE'
			,SAVE_SUCCESS  			: 'Record has been successfully saved.'
			,SAVE_FAILURE 			: 'Database connectivity error: Failure during submission of record.'
			,SAVE_MODIFIED 			: 'Record has been already modified. Would you like to proceed?'
			,SAVE_PERSONNELID		: 'Personnel selected already exists. Please select a different personnel.'
			,DELETE_SUCCESS 		: 'Record has been successfully deleted.'
			,DELETE_USED 			: 'This record can no longer be deleted. This record was already used in another transaction.'
			,DELETE_CONFIRM 		: 'Are you sure you want to delete this record? You can\'t undo this action.'
			,EDIT_UNABLE 			: 'Unable to find this record.'
			,EDIT_USED 				: 'This record is already used and cannot be modified.'
			,NOREC_PRINT 			: 'No records to print.'
			,AJAX_FAILURE 			: 'Database connectivity error. Please make sure you are connected to the network and try again.'
			,REF_EXISTS 			: 'Transaction reference already exists. Would you like to generate another reference?'
			,CANCEL_RECORD 			: 'Are you sure you want to cancel this record? Please note that once you cancel this record, you can no longer undo it.'
			,IS_CLOSEDJE			: 'Transaction period for this month is already closed.'
			
			/** Forms **/
			,FORM_VALID 			: '<em style="color:green">Form is valid.</em>'
			,FORM_INVALID 			: '<em style="color:red">Fields with * are required.</em>'
			,REQ 					: '<em style="color:red;">*</em>'
			,FORM_ISCENTER			: false
			,FORM_HASBORDER			: false
			,REPORTBTN_ISVERTICAL	: false
			// Added by niel (01/12/2023)
			,FORM_INVALID_INPUT		: 'Please fill up all necessary fields.'
			
			/** Fields **/
			,DEF_PAGE_SIZE 			: 50
			,DEF_WIDTH 				: 350
			,DEF_LABEL_WIDTH 		: 135
			,TRANSCONWIDTH			: 750
			
			/** Grids **/
			,HEX_ACTIVE 			: '#3498db'
			,HEX_INACTIVE 			: '#ecf0f1'
			
			/** Others **/
			,DEF_CURRENCY 			: ''
			,DATE_FORMAT			: 'm/d/Y'
			,DEFAULT_EMPTY_IMG		: 'default-no-img.jpg'
			/* values:
			 * 1 = Development Mode
			 * 0 = Deployment Mode
			 */
			,PROJECT_MODE			: 1
			
		};
		
		for( x in params ){
			config[ x.toUpperCase() ] = params[x]
		}
		
		return config;
	}
	
	return {
		getConstants : constantVar
	}
}();