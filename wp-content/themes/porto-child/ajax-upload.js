

$.ajax_upload=function(options){
    $.ajax_upload.prototype.p={
      start:function(callback){},
      progress:function(callback){},
      finish:function(callback){}
    }
    $.ajax_upload.prototype.p.start=options.start;
    $.ajax_upload.prototype.p.progress=options.progress;
    $.ajax_upload.prototype.p.finish=options.finish;

    if(options.file==undefined){
        filename='fileupload_'+Math.floor((Math.random() * 1000) + 1);
        html='<input type="file" id="'+filename+'" name="userfile" style="display:none" />';
        $("body").append(html);
        
     	$('#'+filename).click();
    	$('#'+filename).change(function(){
        file=$("#"+filename).get(0).files[0];
         $.ajax_upload.prototype.p.start(file);

        formdata= new FormData();
        if(options.filename==undefined){
            options.filename="file";
        }
        formdata.append(options.filename,file);

        if(options.data!=undefined){
            for(key in options.data){
                formdata.append(key,options.data[key]);
            }
            
        }

        ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", $.ajax_upload.prototype.p.progress, false);
        if(options.dataType=='json'){
            ajax.addEventListener("load", function(event){
                $.ajax_upload.prototype.p.finish(eval('(' + event.target.responseText + ')'));
            }, false);
            
        }else{
            ajax.addEventListener("load",function(event){ $.ajax_upload.prototype.p.finish(event.target.responseText);} , false);
        }
        
        
        ajax.open("POST", options.url);
        ajax.send(formdata);
        }); 
    }else{
        file=options.file;
         $.ajax_upload.prototype.p.start(file);

        formdata= new FormData();
        if(options.filename==undefined){
            options.filename="file";
        }
        formdata.append(options.filename,file);
       
        if(options.data!=undefined){
            for(key in options.data){
                formdata.append(key,options.data[key]);
            }
            
        }

        ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", $.ajax_upload.prototype.p.progress, false);
        if(options.dataType=='json'){
            ajax.addEventListener("load", function(event){
                $.ajax_upload.prototype.p.finish(eval('(' + event.target.responseText + ')'));
            }, false);
            
        }else{
            ajax.addEventListener("load",function(event){ $.ajax_upload.prototype.p.finish(event.target.responseText);} , false);
        }
        
        
        ajax.open("POST", options.url);
        ajax.send(formdata);
    }


         

    
    

};