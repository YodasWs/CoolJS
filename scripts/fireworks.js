// Displays an Awesome Firework Show! 2-4 Nov 2011
// Copyright 2011, Samuel B Grundman
// Rewritten to use objects to allow multiple fireworks, 7 Nov 2011

var fireworks = new Array();
var numRemnants = 250;
function randInt(min, max) {
	return Math.round(Math.random() * (max - min) + min);
}
function loadCanvas() {
	for (i in arguments) {
		// Do we have canvas control?
		canvasNode = document.getElementById(arguments[i]);
		if (!canvasNode) continue;
		if (canvasNode.getContext) canvas = canvasNode.getContext('2d');
		if (!canvas) {
			canvasNode.style.display = 'none';
			continue;
		}
		fireworks[arguments[i]] = new fireworkObj(arguments[i]);
	}
}

function fireworkObj(canvasId) {
	// Initiate Firework Variables
	this.remnants = new Array();
	this.x = randInt(0, 2) * canvasNode.width / 2
	this.y = canvasNode.height;
	this.canvasId = canvasId;
	this.canvasNode = document.getElementById(this.canvasId);
	this.angle;
	var minA = -50;
	var maxA = 50;
	if (this.x == 0) {
		minA = 30;
		maxA = 70;
	} else if (this.x == canvasNode.width) {
		minA = -70;
		maxA = -30;
	}
	do {
		this.angle = randInt(minA, maxA);
	} while (Math.abs(this.angle) < 30);
	this.angle *= Math.PI / 180;
	this.color = 'rgba(0,0,0,.8)';
	this.runFirework = function() {
		var code = '';
		// Show Firework being launched
		if (this.y > this.canvasNode.height * 2 / 5 + randInt(-5, 5) * this.canvasNode.height / 100) {
			clearCanvas(this.canvasId);
			this.y -= 10 * Math.cos(this.angle);
			this.x += 10 * Math.sin(this.angle);
			code = this.Bind(this.runFirework);
		} else code = this.Bind(this.explodeFirework);
		drawPoint(this.x, this.y, this.color, 5, this.canvasNode.getContext('2d'));
		this.timeout = setTimeout(code, 40);
	};
	this.explodeFirework = function() {
		this.color = 'rgba(' + randInt(0,255) + ',' + randInt(0,255) + ',' + randInt(0,255) + ',' + randInt(5,8)/10 + ')';
		this.remnants = new Array();
		// Initiate Remnants Variables
		for (var i=0; i<numRemnants; i++) {
			this.remnants.push(new remnant(this.x, this.y, this.angle, this.color, this.canvasId));
		}
		var code = this.Bind(this.moveRemnants);
		this.timeout = setTimeout(code, 60);
	};
	this.moveRemnants = function() {
		clearCanvas(this.canvasId);
		var code = '';
		var died = 0;
		for (var i in this.remnants) this.remnants[i].move();
		for (var i=0; i<this.remnants.length; i++)
		if (this.remnants[i].y >= this.canvasNode.height || this.remnants[i].radius <= 1
		|| this.remnants[i].x <= 0 || this.remnants[i].x >= this.canvasNode.width) {
			this.remnants.unshift(this.remnants.splice(i, 1)); died++;
		}
		if (died > 0) this.remnants.splice(0, died);
	 	if (this.remnants.length == 0) {
			clearCanvas(this.canvasId);
			code = 'loadCanvas("' + this.canvasId + '")';
			this.timeout = setTimeout(code, 1000);
		} else {
			code = this.Bind(this.moveRemnants);
			this.timeout = setTimeout(code, 100);
		}
	};
	this.Bind = function(Method) {
		var _this = this;
		return (function(){
			return (Method.apply(_this, arguments));
		});
	}
	this.runFirework();
}
function remnant(x, y, angle, color, canvasId) {
	// Firework Remnant Object
	this.canvasId = canvasId;
	this.canvasNode = document.getElementById(this.canvasId);
	if (!this.canvasNode) return false;
	var newAngle = angle + 180 * Math.cos(randInt(-90, 90) * Math.PI / 180);
	while (newAngle == 0) newAngle = randInt(-1, 1) * Math.PI / 180;
	this.radius = 5;
	this.x = x;
	this.y = y;
	var speed = randInt(0, 10) * Math.cos(newAngle - angle);
	this.dy = speed * Math.cos(newAngle);
	this.dx = speed * Math.sin(newAngle) * 1;
	this.color = color;
	this.time = 0;
	this.move = function() {
		this.y -= this.dy;
		this.x += this.dx;
		this.dy -= this.time++ / 3;
		if (this.time > randInt(10, 15)) this.radius--;
		if (this.y > canvasNode.height * 4 / 5) this.radius--;
		drawPoint(this.x, this.y, this.color, this.radius, this.canvasNode.getContext('2d'));
	};
}
var drawPoint = function(x, y, color, radius, canvas) {
	if (!canvas) return false;
	canvas.fillStyle = color;
	canvas.beginPath();
	canvas.arc(x, y, radius, 0, Math.PI*2, false);
	canvas.closePath();
	canvas.fill();
};
var toggleFireworks = function() {
	for (i in fireworks) if (fireworks[i].timeout) {
		clearTimeout(fireworks[i].timeout);
		fireworks[i].timeout = false;
	} else loadCanvas(fireworks[i].canvasId);
	var btnToggle = document.getElementById('btnToggle');
	if (!btnToggle) return false;
	if (btnToggle.value == 'Stop') btnToggle.value = 'Start';
	else btnToggle.value = 'Stop';
};
var clearCanvas = function(canvasId) {
	var canvas = document.getElementById(canvasId).getContext('2d');
	canvas.clearRect(0, 0, canvasNode.width, canvasNode.height);
};