var mycanvas, mycontext, baseimage, watermark, baseimage, watermark;
var position = "t";
var imgurlpattern = /https*\:\/\/.*.(png|jpg|gif)/;


window.onload = function() {
    mycanvas = document.getElementById('canvas'),
    mycontext = canvas.getContext('2d');
    baseimage = new Image();
    baseimage.src="vanitasbg.jpg";
    $(baseimage).on("load", function(){
        draw();
    });
    watermark = new Image();
    watermark.src="default.png";
    $(watermark).on("load", function(){
        draw();
    });

    watermark.onload = baseimage.onload = function() {
        draw();
    }

    $("#baseinput").change(updateBase);
    $("#watermarkinput").change(updateWatermark);
    $("#baseurl").change(updateBase);
    $("#watermarkurl").change(updateWatermark);
    $("#position").change(updatePosition);
}

function updateBase(event) {
    if(event.target.files) {
        var file = event.target.files[0]; // File
    	if(!file) return false;
    	if(file.type.match('image.*')) {
    	    var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function (e) {
        	    baseimage.src=e.target.result;
            }
        } else alert("Please upload an image file!");
    } else {
        if(!imgurlpattern.test(event.target.value)) {
            alert("Please enter a link to an image file!");
            return false; 
        }
        baseimage.src=event.target.value;
    }
    event.target.value=null;
}



function updateWatermark(event) {
    if(event.target.files) {
        var file = event.target.files[0]; // File
    	if(!file) return false;
    	if(file.type.match('image.*')) {
    	    var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function (e) {
        	    watermark.src=e.target.result;
            }
        } else alert("Please upload an image file!");
    } else {
        if(!imgurlpattern.test(event.target.value)) {
            alert("Please enter a link to an image file!");
            return false; 
        }
        watermark.src=event.target.value;
    }
    event.target.value=null;
}



function updatePosition(event) {
    position = $(event.target).find("option:selected").val();
    draw();
}



function draw() {
    mycontext.clearRect(0, 0, mycanvas.width, mycanvas.height);
    mycanvas.width=baseimage.width;
    mycanvas.height=baseimage.height;
    mycontext.drawImage(baseimage, 0, 0);

    if(position=="t") {
        
        for(var i=0; i < Math.ceil(mycanvas.width/watermark.width); i++) {
            for(var j=0; j<Math.ceil(mycanvas.height/watermark.height); j++) {
                mycontext.drawImage(watermark, i*watermark.width, j*watermark.height);
            }
        }
        
    } else if(position=="s") {
        
        var wRatio = mycanvas.width/watermark.width;
        var hRatio = mycanvas.height/watermark.height;
        var ratio = Math.min(mycanvas.width/watermark.width, mycanvas.height/watermark.height);
        var newW = watermark.width * ratio;
        var newH = watermark.height * ratio;
        mycontext.drawImage(watermark, canvas.width/2-newW/2, canvas.height/2-newH/2, newW, newH);
        
    } else {
        
        let left, top;
        switch(position) {
            case "c":
                left = mycanvas.width/2-watermark.width/2;
                top = mycanvas.height/2-watermark.height/2;
                break;

            case "tr":
                left = mycanvas.width-watermark.width;
                break;

            case "bl":
                top = mycanvas.height-watermark.height
                break;

            case "br":
                left = mycanvas.width-watermark.width
                top = mycanvas.height-watermark.height;
                break;
                
            default: 
                left = 0;
                top = 0;
        }
        mycontext.drawImage(watermark, left, top);
        
    }
}