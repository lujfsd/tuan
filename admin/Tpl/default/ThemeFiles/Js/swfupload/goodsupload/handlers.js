/* Demo Note:  This demo uses a FileProgress class that handles the UI for displaying the file name and percent complete.
The FileProgress class is not part of SWFUpload.
*/


/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileQueued(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Pending...");
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("File is too big.");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("Cannot upload Zero Byte files.");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus("Invalid File Type.");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		default:
			if (file !== null) {
				progress.setStatus("Unhandled Error");
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesSelected > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}
		
		/* I want auto start the upload and I can do that here */
		this.startUpload();
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(file) {
	document.getElementById("loader").style.visibility = "visible";
	document.getElementById("loader").style.display = "block";
	document.getElementById("loader").style.width = "150px";
	document.getElementById("loader").style.position = "absolute";	
	document.getElementById("loader").style.left="";
	document.getElementById("loader").style.right=0;
	document.getElementById("loader").style.top=0;
	
	document.getElementById("loader").innerHTML = file.name+"  上传中...";
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("Uploading...");
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	document.getElementById("loader").innerHTML = "";
	document.getElementById("loader").style.visibility = "hidden";
	document.getElementById("loader").style.display = "none";

	var dataobj = $.evalJSON(serverData); 
	if(dataobj.msg=="")
	{
		document.getElementById("goods_img_box").src = ROOT_PATH+dataobj.data.big_img;
		$("#gallery_list").append("<div><input type='hidden' value='"+dataobj.data.id+"' name='goods_gallerys[]' /><img src='"+ROOT_PATH+dataobj.data.small_img+"' onclick='setgallery("+dataobj.data.id+")' /><br /><a href='javascript:;' onclick='delgallery("+dataobj.data.id+",this)'>删除</a></div>");
		$("#gallery_id").val(dataobj.data.id);
		refresh_img_list_box();
	}
	else
	{
		document.getElementById("uploaderimg").innerHTML = dataobj.msg;
	}
	
}
function uploadSpecSuccess(file,serverData)
{
	document.getElementById("loader").innerHTML = "";
	document.getElementById("loader").style.visibility = "hidden";
	document.getElementById("loader").style.display = "none";
	
	var dataobj = $.evalJSON(serverData); 
	if(dataobj.status)
	{			
		document.getElementById(dataobj.id).src = ROOT_PATH+dataobj.info;
	}
	else
	alert(dataobj.info);
}
function uploadError(file, errorCode, message) {
    
}

function uploadComplete(file) {

}

function queueComplete(numFilesUploaded) {

}
