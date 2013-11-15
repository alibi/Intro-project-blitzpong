// Create Animation Frame
var animate = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(callback) { window.setTimeout(callback, 1000/60) };
// Set up Canvas Variables
var canvas = document.createElement('canvas');
var width = 700;
var height = 400;
canvas.width = width;
canvas.height = height;
var context = canvas.getContext('2d');

// Playing Variables
var player = new Player();
var computer = new Computer();
var ball = new Ball(350, 200);


//attach canvas to screen and step forward
window.onload = function() {
  document.getElementById("pong").appendChild(canvas);
  /* step is responsible for:
        - Update all objects: Paddles and Ball
        - Render all objects
        - requestanimation fram to call step again
    */
  animate(step);
};


function step(){
  update();
  render();
  animate(step);
};



function update(){
  player.update();
  computer.update(ball);
  ball.update(player.paddle,computer.paddle);
};


function render(){
  // board color
  context.fillStyle = "#669999";
  //Fill the rectangle
  context.fillRect(0,0,width,height);
  player.render();
  computer.render();
  ball.render();
};

function Paddle(x,y,width,height){
  this.x = x;
  this.y = y;
  this.width = width;
  this.height = height;
  this.x_speed = 0;
  this.y_speed = 0;
}

 Paddle.prototype.render= function(){
  context.fillStyle = "#CCCCCC";
  context.fillRect(this.x,this.y,this.width,this.height);
};

Paddle.prototype.move = function(x, y) {
  this.x += x;
  this.y += y;
  this.x_speed = x;
  this.y_speed = y;
  if(this.y < 0) { // all the way to the top
    this.y = 0;
    this.y_speed = 0;
  } else if (this.y + this.height > 400) { // all the way to the bottom
    this.y = 400 - this.height;
    this.y_speed = 0;
  }
}


function Player(){ 
  this.paddle = new Paddle(680,175,10,50);
}

Player.prototype.render= function(){
  this.paddle.render();
};

Player.prototype.update = function() {
  for(var key in keysDown) {
    var value = Number(key);
    if(value == 38) { // up arrow
      this.paddle.move(0, -4);
    } else if (value == 40) { // down arrow
      this.paddle.move(0, 4);
    } else {
      this.paddle.move(0, 0);
    }
  }
};



function Computer(){
  this.paddle = new Paddle(10,175,10,50);
}

Computer.prototype.update = function(ball) {
  var y_pos = ball.y;
  var diff = -((this.paddle.y + (this.paddle.width / 2)) - y_pos);
  if(diff < 0 && diff < -4) { // max speed left
    diff = -5;
  } else if(diff > 0 && diff > 4) { // max speed right
    diff = 5;
  }
  this.paddle.move(0, diff);
  if(this.paddle.y < 0) {
    this.paddle.y = 0;
  } else if (this.paddle.y + this.paddle.width > 400) {
    this.paddle.y = 400 - this.paddle.width;
  }
};


Computer.prototype.render = function(){
  this.paddle.render();
};

function Ball(x, y) {
  this.x = x;
  this.y = y;
  this.x_speed = 3;
  this.y_speed = 0;
  this.radius = 5;
}

Ball.prototype.render = function() {
  context.beginPath();
  context.arc(this.x, this.y, this.radius, 2 * Math.PI, false);
  context.fillStyle = "#000000";
  context.fill();
};

Ball.prototype.update = function(paddle1,paddle2){
  this.x += this.x_speed;
  this.y += this.y_speed;
  var top_x = this.x - 5;
  var top_y = this.y - 5;
  var bottom_x = this.x + 5;
  var bottom_y = this.y + 5;

  // hitting the top wall
  if(this.y - 5 < 0) {
    this.y = 5;
    this.y_speed = -this.y_speed;
    // hitting the bottom wall
  } else if(this.y + 5 > 400) { 
    this.y = 395;
    this.y_speed = -this.y_speed;
  }

  // a point was scored
  if(this.x < 0 || this.x > 700) {
    if(this.x < 0){
      var cur = parseInt($("#rightScore").text());
      $("#rightScore").text(cur+1);
    }
    if(this.x > 700){
      var cur = parseInt($("#leftScore").text());
      $("#leftScore").text(cur+1);
    }
    this.x_speed = 3;
    this.y_speed = 0;
    this.x = 350;
    this.y = 200;
  }

  // only check the paddle that the ball is headed to 
  if(top_x > 350) {
    if(top_y < (paddle1.y + paddle1.height) && bottom_y > paddle1.y && top_x < (paddle1.x + paddle1.width) && bottom_x > paddle1.x) {
      // hit the player's paddle
      this.x_speed = -3;
      this.y_speed += (paddle1.y_speed / 2);
      this.x += this.x_speed;
    }
  } else {
    if(top_y < (paddle2.y + paddle2.height) && bottom_y > paddle2.y && top_x < (paddle2.x + paddle2.width) && bottom_x > paddle2.x) {
      // hit the computer's paddle
      this.x_speed = 3;
      this.y_speed += (paddle2.y_speed / 2);
      this.x += this.x_speed;
    }
  }
};

//create a variable to store pressed keys
var keysDown = {};
window.addEventListener("keydown", function(event) {
  keysDown[event.keyCode] = true;
});

window.addEventListener("keyup", function(event) {
  delete keysDown[event.keyCode];
});
