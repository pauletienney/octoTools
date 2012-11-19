/*
	name: functions.js
	for: rassemble un emsemble de fonctions outils
	pour les différentes fonctions javascript
*/

/*==================== VAR GLOBALES ====================*/

var ctx;

/*==================== METHODES GLOBALES ====================*/


function clone(obj){
    if(obj == null || typeof(obj) != 'object')
        return obj;

    var temp = new obj.constructor();
    for(var key in obj)
        temp[key] = clone(obj[key]);

    return temp;
}

/*
@param:
@return:
*/
function addEvent( obj, evt, fn ){
	if ( typeof obj.addEventListener != undefined ){
		obj.addEventListener( evt, fn, false );
	}
	else if ( typeof obj.attachEvent != undefined ) {
		obj.attachEvent( "on" + evt, fn );
	}
}

/*
@param:
@return: 
*/
function addLoadListener(func) {
  if (window.addEventListener) {
    window.addEventListener("load", func, false);
  } else if (document.addEventListener) {
    document.addEventListener("load", func, false);
  } else if (window.attachEvent) {
    window.attachEvent("onload", func);
  }
}

/*
@param: 
@return: 
*/
function addResizeListener(func) {
  if (window.addEventListener) {
    window.addEventListener("resize", func, false);
  } else if (document.addEventListener) {
    document.addEventListener("resize", func, false);
  } else if (window.attachEvent) {
    window.attachEvent("onresize", func);
  }
}

/*
@param: an HTML object
@return: the height of obj 
*/
function getH(obj){
	if(obj.offsetHeight) return parseInt(obj.offsetHeight);
	else return parseInt(obj.style.pixelHeight);
}

/*
@param: an HTML object
@return: the width of obj
*/
function getW(obj){
	if(obj.offsetWidth)return parseInt(obj.offsetWidth);
	else return parseInt(obj.style.pixelWidth);
}

/*
@param: an HTML object and and Integer number
@return: null
*/
function setH(obj, h){
	if(obj.offsetHeight) obj.offsetHeight = h;
	else obj.style.pixelHeight = h;
}

/*----------------------------------------------------*/
/*
@param: an HTML object and and Integer number
@return: null
*/
function setW(obj, w){
	if(obj.offsetWidth) obj.offsetWidth = w;
	else obj.style.pixelWidth = w;
}

/*----------------------------------------------------*/
/*
@param: an HTML object
@return: the left position of obj
*/
function getL(obj){
	if(obj.offsetLeft)return parseInt(obj.offsetLeft);
	else return parseInt(obj.style.left);
	//l = obj.offsetLeft; // On récupère la position absolue initiale.
	//return parseInt(l);
}

/*----------------------------------------------------*/
/*
@param: an HTML object and and Integer number
@return:  null
*/
function setL(obj, l){
	s = obj.style;
	s.left = String(l)+'px';
}
/*----------------------------------------------------*/
/*
@param: an HTML object
@return: the left position of obj
*/
function getT(obj){
	if(obj.offsetTop)return parseInt(obj.offsetTop);
	else return parseInt(obj.style.top);
	//l = obj.offsetTop; // On récupère la position absolue initiale.
	//return parseInt(l);
}

/*----------------------------------------------------*/
/*
@param: an HTML object and and Integer number
@return:  null
set the top position
*/
function setT(obj, l){
	s = obj.style;
	s.top = String(l)+'px';
}

/*----------------------------------------------------*/
/*
@param: Nan
@return:  client window width
*/
function getClientWidth(){ 
	if(navigator.userAgent.indexOf('MSIE') != -1) return document.body.clientWidth;
	else return window.innerWidth;
}

/*----------------------------------------------------*/
/*
@param: nan
@return:  client window height
*/
function getClientHeight(){
	if(navigator.userAgent.indexOf('MSIE') != -1) return document.body.clientHeight;
	else return window.innerHeight;
}

/*----------------------------------------------------*/
/*
@param: nan
@return:  array du scroll fenetre -- 0 -> X   1 -> Y
*/

function getScrollPosition()
{
	return Array((document.documentElement && document.documentElement.scrollLeft) || window.pageXOffset || self.pageXOffset || document.body.scrollLeft,(document.documentElement && document.documentElement.scrollTop) || window.pageYOffset || self.pageYOffset || document.body.scrollTop);
}

/*----------------------------------------------------*/
/*
@param:
@return:
*/
function getXMLHttpRequest(){
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject){
		if (window.ActiveXObject){
			try{
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e){
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} 
		else{
			xhr = new XMLHttpRequest(); 
		}
	}
	else{
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	return xhr;
}

function getXDomainRequest() {
	var xdr = null;
	
	if (window.XDomainRequest) {
		xdr = new XDomainRequest(); 
	} else if (window.XMLHttpRequest) {
		xdr = new XMLHttpRequest(); 
	} else {
		alert("Votre navigateur ne gère pas l'AJAX cross-domain !");
	}
	
	return xdr;	
}

/*
@param:
@return:
*/
function refresh(page){
	document.location.replace(page);
	checkSession();
}

/*
@param:
@return:
*/
function inc(filename){
	if(!isIncluded(filename)){
			var body = $('body');
			script = document.createElement('script');
			script.src = 'bin/js/' + filename;
			script.type = 'text/javascript';
			var id = filename.substr(0, filename.length-3);
			script.id = id;
			body.appendChild(script);
	}
}


/*
@param:
@return:
*/
function isIncluded(filename){
	var included = false;
	for(var i = 0 ; i < document.getElementsByTagName('script').length ; i++){
		if(document.getElementsByTagName('script')[i].id == filename.substr(0, filename.length-3)){
			included = true;
		}
	}
	return included;
}

/*
@param:
@return:
*/
function removeJS(filename){
	var removed = false;
	for(var i = 0 ; i < document.getElementsByTagName('script').length ; i++){
		if(document.getElementsByTagName('script')[i].id == filename.substr(0, filename.length-3)){
			$('body').removeChild(document.getElementsByTagName('script')[i]);
			removed = true;
		}
	}
	return removed;
}


function generateId(l){
	var id = "#";
	var abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var abc1 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	id += abc[Math.floor(Math.random()*51)];
	for(var i = 0 ; i < (l - 1) ; i++){
		id += abc1[Math.floor(Math.random()*61)];
	}
	
	return id;
}


function randomColor(type){
	var min = 40;
	if(type == 3){
		min = 40;
	}
	if(type == 2){
		min = 80;
	}
	if(type == 1){
		min = 150;
	}
	
	var max = 255;
	var n = 0;
	while(n < min) var n = Math.floor(Math.random()*max);
	return n;
}

function lighten(val, percent){
	return parseInt(val + (val * percent/100));
}

function darken(val, percent){
	return parseInt(val - (val * percent/100));
}

function colorNormal(type, id){
	var r = randomColor(type);
	var g = randomColor(type);
	var b = randomColor(type);
	var textColor = "rgb(" + darken(r, 50) + ", " + darken(g, 50) + ", " + darken(b, 50) + ")";
	var el = $(id);
		el.style.backgroundColor = "rgb(" + r + ", " + g + ", " + b + ")";
		el.style.color = textColor;
		el.style.opacity = '1';//'0.8';
		el.style.textShadow = '0 1px ' + "rgb(" + lighten(r, 40) + ", " + lighten(g, 40) + ", " + lighten(b, 40) + ")";
}

function colorGradient(type, id){
	var r = randomColor(type);
	var g = randomColor(type);
	var b = randomColor(type);
	var textColor = "rgb(" + darken(r, 50) + ", " + darken(g, 50) + ", " + darken(b, 50) + ")";
	var el = $(id);
		el.style.background = "-webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, " + "rgb(" + r + ", " + g + ", " + b + ")" + "), color-stop(100%, " + "rgb(" + darken(r, 20) + ", " + darken(g, 20) + ", " + darken(b, 20) + ")" +")";
		el.style.background = "-webkit-linear-gradient(bottom, " + "rgb(" + darken(r, 20) + ", " + darken(g, 20) + ", " + darken(b, 20) + ")" +", " + "rgb(" + r + ", " + g + ", " + b + ")" + ")";
		el.style.background = "-moz-linear-gradient(bottom, " + "rgb(" + darken(r, 20) + ", " + darken(g, 20) + ", " + darken(b, 20) + ")" +", " + "rgb(" + r + ", " + g + ", " + b + ")" + ")";
		el.style.background = "-o-linear-gradient(bottom, " + "rgb(" + darken(r, 20) + ", " + darken(g, 20) + ", " + darken(b, 20) + ")" +", " + "rgb(" + r + ", " + g + ", " + b + ")" + ")";
		el.style.background = "-ms-linear-gradient(bottom, " + "rgb(" + darken(r, 20) + ", " + darken(g, 20) + ", " + darken(b, 20) + ")" +", " + "rgb(" + r + ", " + g + ", " + b + ")" + ")";
		el.style.background = "linear-gradient(bottom, " + "rgb(" + darken(r, 20) + ", " + darken(g, 20) + ", " + darken(b, 20) + ")" +", " + "rgb(" + r + ", " + g + ", " + b + ")" + ")";

		el.style.color = textColor;
		el.style.opacity = '1';//'0.8';
		el.style.textShadow = '0 1px ' + "rgb(" + lighten(r, 40) + ", " + lighten(g, 40) + ", " + lighten(b, 40) + ")";
}

function validPass(pass){
	return /^[a-zA-Z0-9]{4,30}$/.test(pass);
	//return /^[\.]{4,30}$/.test(pass);
}

function validMail(mail){
	return /^([a-zA-Z0-9._\-]+)@([a-zA-Z0-9._-]+)\.([a-z]{2,6})$/.test(mail);
}

function validPseudo(pseudo){
	return /^[a-zA-Z0-9]{2,30}$/.test(pseudo);
}

function sendForm(pagePhp, args, innerResponseIn){
	var xhr = getXMLHttpRequest();

	xhr.open('POST', pagePhp);

	var form = new FormData();
	for(var id in args){
		form.append(id, args[id]);
	}
	xhr.onreadystatechange = function() {
		if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)){
			if(innerResponseIn)	innerResponseIn.innerHTML = xhr.responseText;
		}
	};
	xhr.send(form);
}

function initCanvas(args, inElement){
	var canvas = document.createElement('canvas');
		canvas.width = args["width"];
		canvas.height = args["height"];
		canvas.id = args["id"];
	document.getElementById(inElement).appendChild(canvas);
	var can = document.getElementById(args["id"]);
	if(!can || !can.getContext){
		return false;
	}
	ctx = can.getContext('2d');
	if(!ctx){
		return false;
	}
	document.getElementById(inElement).style.width = args["width"] + "px";
	return true;
}

function deleteCanvas(id, inElement){
	document.getElementById(inElement).removeChild(document.getElementById(id));
}