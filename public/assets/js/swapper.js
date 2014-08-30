var Swapper = function(){

	this.animate = true;

	this.request = function(url){
		
		repeatRequest = url;

		$.get(url+'?swapper_request=true',function(data){

		}).success(function(data){
		console.log(data);
			if(typeof data ==='object'){
				if(!jQuery.isEmptyObject(data)){

					if(data.swapper_newpage === true){
						window.location = data.swapper_newpage_link;
						return;
					}

					if(data.swapper_error === true){
						Swapper.error(data.swapper_error_message);
					}else{
						Swapper.handleData(data,url);
						Swapper.found();
					}
				}else{

				}
			}else{
				
			}
			
		}).fail(function(data){
			Swapper.notFound();
		});
	}

	this.notFound = function(){
		alert(' 404 - Page not found ');
	}

	this.found = function(){

	}

	this.error = function(error){
		alert(error);
	}

	this.success = function(){

	}

	this.handleData = function(data){
		//alert('this is data '+data);
		
		
		
		if(Swapper.animate){
			$.each(data, function(key, value){
				$('[data-block~="'+key+'"]').fadeOut("fast",function(){
					var content = $(value).hide();
					$(this).replaceWith(content);
					$('[data-block~="'+key+'"]').fadeIn("fast");
				});
			});
		}else{
			$.each(data, function(key, value){
				$.each(data, function(key, value){
					$('[data-block~="'+key+'"]').replaceWith(value);
				});
			});
		
		}
		
		Swapper.finished();
		
	}

	this.finished = function(){

	}

	this.isCompatible = function(){
		if (window.history && window.history.pushState){
			return true;
		}
		return false;
	}	

	this.registerLinkHandler = function(){

		$(document).on('click','[data-link]',function(event){
			event.preventDefault();
			var stateObj = { url: event.currentTarget.href};

			history.pushState(stateObj,'',event.currentTarget.href);
			Swapper.request(event.currentTarget.href);
			
		});

	}

	this.registerFormHandler = function(){

		$(document).on('submit','[data-submit]',function(event){
			event.preventDefault();
			var fd = new FormData();
			var inputs = $(this).find('input');
			$.each(inputs ,function(i, obj){

					var name = $(obj).attr('name');
					var type = $(obj).attr('type');
					var value = $(obj).val();

					if(type == 'file'){
						var value = $(obj).prop('files');
						var value = value[0];
					}

					fd.append(name,value);

			});
			fd.append('swapper_request',true);

			 var xhr = new XMLHttpRequest();
			 xhr.open('POST', $(this).attr('action'),true);

			
			// Commented out because form submits should not be part of history!
			
			//var stateObj = { url: $(this).attr('action')};

			//history.pushState(stateObj,'',$(this).attr('action'));



			 xhr.onload = function() {
			 	console.log(this.response);
			 	Swapper.handleData(jQuery.parseJSON(this.response));


			 }
			 xhr.send(fd);


		});

	}



}

Swapper = new Swapper;


$(document).ready(function(){


	
window.onpopstate = function(event) {

  Swapper.request(event.state.url);

};


	if(Swapper.isCompatible()){

		Swapper.registerLinkHandler();

		Swapper.registerFormHandler();
	}




});
