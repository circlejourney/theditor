var mycanvas, mycontext, baseimage, watermark, baseimage, watermark;
var position = "t";

window.onload = function() {
    mycanvas = document.getElementById('canvas'),
    mycontext = canvas.getContext('2d');
    baseimage = new Image();
    baseimage.src="vanitasbg.jpg";
    watermark = new Image();
    watermark.src="default.png";

    watermark.onload = baseimage.onload = function() {
        draw();
    }
    
    $("#baseinput").change(updateBase);
    $("#watermarkinput").change(updateWatermark);
    $("#position").change(updatePosition)
}

function updateBase(event) {
    var file = event.target.files[0]; // File
	if(!file) return false;
	
	if(file.type.match('image.*')) {
	    var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function (e) {
    	    baseimage.src=e.target.result;
    	    baseimage.onload=function() {
                draw();
    	    }
        }

    } else alert("Please upload an image!");
}

function updateWatermark(event) {
    var file = event.target.files[0]; // File
	
	if(!file) return false;
	
	if(file.type.match('image.*')) {
	    var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function (e) {
    	    watermark.src=e.target.result;
        }

    } else alert("Please upload an image!");
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
        var ratio;
        wRatio < hRatio ? ratio = wRatio : ratio = hRatio;
        var newW = watermark.width * ratio;
        var newH = watermark.height * ratio;
        mycontext.drawImage(watermark, canvas.width/2-newW/2, canvas.height/2-newH/2, newW, newH);
        
    } else {
        
        var left=0;
        var top=0;
        
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