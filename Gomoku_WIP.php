<?php
require "heading-nav.php";
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="external.css">
<meta charset="UTF-8">
</head>

<body onload=""> -->
<div>
<div id="userInfo"> <?php echo "Currently signed in: ".$_SESSION['usernameloggedin'];?> </div> <!-- Display the current user logged in here. -->
<div id="clock"> clock </div>
<div id="content_box">
<table>
<tr>
<td>
<canvas style="border:3px solid #000000; background: url('')" id="canvas" width="1000" height="1000" onclick="place(event)"></canvas>

</td>
<td>
<div id="display_box">
<h2>Player 1 Runs:</h2>
<div>
<span><label for="player1_3">3's:</label><input type="text" name="player1_3" id="player1_3" value="0"></span><br>
<span><label for="player1_4">4's:</label><input type="text" name="player1_4" id="player1_4" value="0"></span><br>
<span><label for="player1_5">5's:</label><input type="text" name="player1_5" id="player1_5" value="0"></span><br>
</div>
<br>
<h2>Player 2 Runs:</h2>
<div>
<span><label for="player2_3">3's:</label><input type="text" name="player2_3" id="player2_3" value="0"></span><br>
<span><label for="player2_4">4's:</label><input type="text" name="player2_4" id="player2_4" value="0"></span><br>
<span><label for="player2_5">5's:</label><input type="text" name="player2_5" id="player2_5" value="0"></span><br>
</div>
</td>
</tr>
<tr>
<td>
<button type="button" id="frognut" onclick="createGame(1000,19)">New Game: 19 Rows</button> 
<button type="button" id="frognut1" onclick="createGame(1000,15)">New Game: 15 Rows</button> 
<button type="button" id="frognut4" onclick="playForMe(NewGame,0,0,true)">Give me a hint!</button> 
<label for="depth">Depth:</label>
<input type="text" name="depth" id="searchDepth" value="1">
<label for="breadth">Breadth</label>
<input type="text" name="breadth" id="searchWidth" value="10">
<button type="button" id="frognut" onclick="changeColors()">Change color scheme</button> 
</td>
</tr>
</table>
</div>

</div>
<br>
<div id="EventBox">Currently, there have been no events worth mentioning.</div>

</body>
<script>

var size;
var rows;
var boardPadding = 4; //value in pixels to adjust text in pieces by, also the variable by which to pad the 2d array that holds the pieces on the board
var NewGame;
var canvas = document.getElementById("canvas");
var context = canvas.getContext("2d");
var score = 0; //decide how to implement this later
var duration; //array of form [minutes, seconds]
var beginTime;
var gameOver;
var temp; 
var NewGame2;
var depth;
var width;
var runs;
var colors = ["#DB0032","#002e6D","#007934"];
var backgrounds = ["https://cdn.shoplightspeed.com/shops/628395/files/19154338/1600x2048x1/japanese-woodgrain-blond-6721-40-x-31.jpg",
					"https://www.agedwoods.com/wp-content/uploads/2018/03/agedwoods-whyisthegrainimportant-benefitsofreclaimedwood-2018.jpg",
					"https://cdn.filtergrade.com/wp-content/uploads/2015/10/03034617/2.jpg"] //these are background images, I decided to go with solid color bacgroudns instead
var colori = 0;
const reducer = (previousValue, currentValue) => previousValue + currentValue;
function dislplayUpdate(runs){
	document.getElementById("player1_3").value = runs[0][0][0];
	document.getElementById("player1_4").value = runs[0][0][1];
	document.getElementById("player1_5").value = runs[0][0][2];
	document.getElementById("player2_3").value = runs[1][0][0];
	document.getElementById("player2_4").value = runs[1][0][1];
	document.getElementById("player2_5").value = runs[1][0][2];
}
function changeColors(){ //this function changes the index that governs what colors　various parts of the game are drawn in
	colori = (colori +1)%3;
	console.log(colori);
}
function swapGames(){ //this is a ultiliy function that is not used
	temp = NewGame;
	NewGame = NewGame2;
	NewGame2 = temp;
	console.log("swapped");
}
function copyGame(){ //this is a ultiliy function that is not used
	NewGame2 = NewGame.copy();
	console.log("copied");
}
function errorReport(x, error){ //this is a ultility function for reporting errors and various status messages
	if(error){
		document.getElementById("EventBox").innerHTML= "Error: " + x;
	}
	else{document.getElementById("EventBox").innerHTML=x;}
}
class piece{ //this is piece class. You'll never guess what its for. :p
	constructor(color, turn, x, y, played){
		this.color = color;
		this.turn = turn;
		this.x = x;
		this.y = y;
		this.played = played;
		
	}
}
class Game{ //this is the game class
	constructor(size,rows,board,turn,color,gameOver){
		if(board === undefined){
			this.gameOver = false;
			this.size = size;
			this.rows = rows;
			this.board = new Array();
			for(let x=0;x<rows+(boardPadding*2);x+=1){
				this.board[x] = new Array(rows+(boardPadding*2)).fill(new piece(0,0,0,0,false))
			}
			this.turn = 1; //turn number
			this.color=false; //color who's turn it is, black or white, or whatever colors you pass when drawing the board.
		}
		else{
			this.gameOver = gameOver;
			this.size = size;
			this.rows = rows;
			this. board = board;
			this.turn = turn;
			this.color = color;
		}
	}
	copy(){
		return(new Game(this.size,this.rows,JSON.parse(JSON.stringify(this.board)),this.turn,this.color,this.gameOver))
	}
	play(x,y){
		if (!this.board[x+4][y+4].played){
			this.board[x+4][y+4] = new piece(this.color,this.turn,x,y, true);
			this.color = !this.color;
			this.turn +=1;
		}
		else{
		errorReport("Illegal Move, space already occupied.");
		}
	}
	getPieces(){ //this function returns two arrays, one for each color of piece
		let rows = this.rows;
		let whites = [];
		let blacks = [];
		let coords = [];
		for(let x=boardPadding-1;x<rows+boardPadding;x+=1){
			for(let y=boardPadding-1;y<rows+boardPadding;y+=1){
				if(this.board[x][y].played == true){
					coords.push(x+","+y+"| ");
					if(this.board[x][y].color == false){
						
						whites.push(this.board[x][y])
					}
					else{
						blacks.push(this.board[x][y])
					}
				}
			}
		}
		return[whites,blacks]
	}
}
function createGame(size,rows){ //this is the function that starts the game and cretes the event listener thatcontrols the game
	NewGame = new Game(size,rows);
	gameOver = false;
	var pieces = NewGame.getPieces();
	drawBoard(context, size, rows, pieces);
	beginTime = performance.now();
	duration = timer();
	runs = new Array(2);
	
	document.body.addEventListener( 'click', function ( event ) {
		if( event.target.id == 'canvas' ) {
			if(!NewGame.gameOver){
				place(event,NewGame);
				pieces = NewGame.getPieces();
				drawBoard(context, size, rows, pieces);
				runs[0] = getRuns(pieces[0],NewGame.board, NewGame.rows); //this returns the number of runs of 3, 4, and 5 in q row, as well as potential next moves
				runs[1] = getRuns(pieces[1],NewGame.board, NewGame.rows);
				dislplayUpdate(runs);
				if (runs[0][0][2]!=0){ //these two blocks check wether the game has been won
					NewGame.gameOver = true;
					let winner = false; //player one, white, 0
					gameOver = true;
					endGame(winner);
					callOut(NewGame.turn-1, score, duration, winner) //since the turn is advanced after every piece is played, one is subtracted to get the trn that was just played
				}
				if (runs[1][0][2]!=0){ //these two blocks check wether the game has been won
					NewGame.gameOver = true;
					let winner = true; //player two, black, 1
					gameOver = true;
					endGame(winner);
					callOut(NewGame.turn-1, score, duration, winner) //since the turn is advanced after every piece is played, one is subtracted to get the trn that was just played
				}
			}
		};
	});
	
}
function playForMe(game, depth, width, root){ //this is an expirimental bounded adversarial search function to reccomend moves
	//this works with kind of a dirty hack, the function needs to be called initially with a boolean true as its last argument.
	//internally when implementing recursion it is called with a boolean false.
	//by doing this, the root level has different/extra functionality.
	if(root){
		 depth = document.getElementById("searchDepth").value;
		 width = document.getElementById("searchWidth").value;
	}
	var moves = [];
	var results = [];
	var pieces = game.getPieces();
	var runs = [];
	runs[0] = getRuns(pieces[0], game.board, game.rows);
	runs[1] = getRuns(pieces[1], game.board, game.rows);
	var potentialMovesWhite = runs[0][1];
	var potentialMovesBlack = runs[1][1];
	let whiteScore = runs[0][0][0] + runs[0][0][1] + runs[0][0][2];
	let blackScore = runs[1][0][0] + runs[1][0][1] + runs[1][0][2];
	let score = whiteScore - blackScore;
	if ((whiteScore == 0) && (blackScore == 0)){score = .01;}
	var whiteWins = .01;
	var blackWins = .01;
	var widthX = width;
	
	//console.log(runs[0][0]);
	
	if (!runs[0][0][2]==0){return([1,0.001,score]);} //check to see if the game has already been won
	if (!runs[1][0][2]==0){return([0.001,1,score]);} //or lost
	if(root){widthX = 1000;}// this line ensures increases the width of the search on the first level so that the computer will always reccomend a winning move if one is immediately available
	
	if (game.color == 0){
		moves = potentialMovesWhite[0].concat(potentialMovesBlack[0]).concat(potentialMovesWhite[1]).concat(potentialMovesBlack[1]).concat(potentialMovesWhite[2]); 
	}
	else{
		moves = potentialMovesBlack[0].concat(potentialMovesWhite[0]).concat(potentialMovesBlack[1]).concat(potentialMovesWhite[1]).concat(potentialMovesBlack[2]); 
	}
	if((depth>0)){ //checks the current depth of recursion and if teh game has been decided
		moves = Array.from(new Set(moves));
		for(let i=0;(i<widthX) && (i<moves.length);i++){
			let x = game.copy(); //creates a new instance of the game
			x.play(moves[i][0],moves[i][1]) //plays the potential move on the new game
			results.push([moves[i],playForMe(x, depth-1, width, false)]); // this is the recursive bit
		}
		results.forEach(element =>{
			whiteWins += (element[1][0]/10);
			blackWins += (element[1][1]/10);
			score += (element[1][2]/10);
			//console.log("at depth "+depth+": "+element[1][0] +" "+ element[1][1] +" "+ element[1][2] + " " + element[0] + " " + element[1]);
		})
		//console.log(whiteWins +" "+ blackWins +" "+ score);
		if(!root){
			return(whiteWins,blackWins,score);
		}
		
	}
	else{
	//console.log("returning default");
		return([.01,.01,score]);
	}
	
		results.sort(function (a, b){
			if(!game.color){
			return (b[1][2]) - (a[1][2]);
			}
			else{
			return (a[1][2]) - (b[1][2]);
			}
		});
		
	
		results.sort(function (a, b){
			if(!game.color){
				return (b[1][0]/b[1][1]) - (a[1][0]/a[1][1]);
			}
			else{
				return (a[1][0]/a[1][1]) - (b[1][0]/b[1][1]);
			}
		});
	

	
		context.beginPath();
		context.arc((results[0][0][0])*(game.size/game.rows),(results[0][0][1])*(game.size/game.rows),((game.size/game.rows)/2)-1,0,2*Math.PI);
		context.fillStyle = "#FF00FF"; //make this selectable later
		context.fill();
		console.log(results[0][0][0] + " " + results[0][0][1]);
		
		if(false){
			results.forEach(a =>{
				console.log((a[1][0]) + " " + a[1][1] + " " + a[1][2] + " " + a[0]);
			})
		}
		if(root){return 0;}
		
		
		
		
	alert("this should not happen.");
}
// callOut(NewGame.turn-1, score, duration, winner)
function callOut(turn, score, duration, winner){ //This is waiting to be written by my partner, this is the hook into the server side
	httpRequest = new XMLHttpRequest(); // create the object
		if (!httpRequest) { 
		  alert('Cannot create an XMLHTTP instance');
		  return false;
		}
		httpRequest.onreadystatechange = Unloaddb;
		httpRequest.open('POST','send.php');  
		httpRequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		httpRequest.send('turns='+ turn+'&score='+ score+'&duration='+ duration+'&winner'+ winner); // send the variables with value set defined by str
}
function endGame(winner){//this function is called when the game ends, currently it simply reports the end of the game and the winner to the eventBox
	x = "1";
	if (winner){
		x = "2";
		
	}
	//dislplayUpdate(runs);
	errorReport("The game is over. Winner is player " + x + ".",false);
	
}

function getRuns(pieces, board, rows){//this function finds how many 3, 4, and 5 in a row a player has
	var pot4 = [];
	var pot5 = [];
	var potAdj = [];
	var whitePotential = []; //program in potential plays later
	var white3Runs = 0;
	var white4Runs = 0;
	var white5Runs = 0;
	pieces.forEach(element =>{ //calculates runs for white
		let x = element.x + boardPadding;
		let y = element.y + boardPadding;
		let color = element.color;
				if((board[x+1][y].played && ((board[x+1][y].color) == color)) && board[x+2][y].played && ((board[x+2][y].color) == color)){ //test for three in a row
					if((board[x+3][y].played && ((board[x+3][y].color) == color))){ //test for four in a row
						if((board[x+4][y].played && ((board[x+4][y].color) == color))){ //test for five in a row
					white5Runs+=1;
				}
				else{
					white4Runs+=1;
					if((!board[x+4][y].played) && (x+4 < rows+ boardPadding) && (y <= rows+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x+4,y]);
					x += boardPadding;
					y += boardPadding;
					}
					if((board[x-1][y].played == false) && (x-1 > 0+ boardPadding) && (y <= rows+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x-1,y]);
					x += boardPadding;
					y += boardPadding;
					}
				}
			}
			else{
				white3Runs+=1;
				if(!board[x+3][y].played && (x+3 < rows+ boardPadding) && (y <= rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x+3,y]);
				x += boardPadding;
				y += boardPadding;
				}
				if(!board[x-1][y].played && (x-1 > 0+ boardPadding) && (y <= rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x-1,y]);
				x += boardPadding;
				y += boardPadding;
				}
			}
		}
		if((board[x][y+1].played && ((board[x][y+1].color) == color)) && board[x][y+2].played && ((board[x][y+2].color) == color)){ //test for three in a row
			if((board[x][y+3].played && ((board[x][y+3].color) == color))){ //test for four in a row
				if((board[x][y+4].played && ((board[x][y+4].color) == color))){ //test for five in a row
					white5Runs+=1;
				}
				else{
					white4Runs+=1;
					if(!board[x][y+4].played && (x < rows+ boardPadding) && (y+4 < rows+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x,y+4]);
					x += boardPadding;
					y += boardPadding;
					}
					if(!board[x][y-1].played && (x > 0+ boardPadding) && (y-1 > 0+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x,y-1]);
					x += boardPadding;
					y += boardPadding;
					}
				}
			}
			else{
				white3Runs+=1;
				if(!board[x][y+3].played && (x < rows+ boardPadding) && (y+3 < rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x,y+3]);
				x += boardPadding;
				y += boardPadding;
				}
				if(!board[x][y-1].played && (x > 0+ boardPadding) && (y-1 > 0+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x,y-1]);
				x += boardPadding;
				y += boardPadding;
				}
			}
		}
		if((board[x+1][y+1].played && ((board[x+1][y+1].color) == color)) && board[x+2][y+2].played && ((board[x+2][y+2].color) == color)){ //test for three in a row
			if((board[x+3][y+3].played && ((board[x+3][y+3].color) == color))){ //test for four in a row
				if((board[x+4][y+4].played && ((board[x+4][y+4].color) == color))){ //test for five in a row
					white5Runs+=1;
				}
				else{
					white4Runs+=1;
					if(!board[x+4][y+4].played && (x+4 < rows+ boardPadding) && (y+4 < rows+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x+4,y+4]);
					x += boardPadding;
					y += boardPadding;
					}
					if(!board[x-1][y-1].played && (x-1 > 0+ boardPadding) && (y-1 > 0+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x-1,y-1]);
					x += boardPadding;
					y += boardPadding;
					}
				}
			}
			else{
				white3Runs+=1;
				if(!board[x+3][y+3].played && (x+3 < rows+ boardPadding) && (y+3 < rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x+3,y+3]);
				x += boardPadding;
				y += boardPadding;
				}
				if(!board[x-1][y-1].played && (x-1 > 0+ boardPadding) && (y-1 <= rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x-1,y-1]);
				x += boardPadding;
				y += boardPadding;
				}
			}
		}
		
		if((board[x-1][y+1].played && ((board[x-1][y+1].color) == color)) && board[x-2][y+2].played && ((board[x-2][y+2].color) == color)){ //test for three in a row
			if((board[x-3][y+3].played && ((board[x-3][y+3].color) == color))){ //test for four in a row
				if((board[x-4][y+4].played && ((board[x-4][y+4].color) == color))){ //test for five in a row
					white5Runs+=1;
				}
				else{
					
					white4Runs+=1;
					if(!board[x-4][y+4].played && (x-4 > 0+ boardPadding) && (y+4 < rows+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x-4,y+4]);
					x += boardPadding;
					y += boardPadding;
					}
					if(!board[x+1][y-1].played && (x+1 <= rows+ boardPadding) && (y-1 > 0+ boardPadding)){
					x -= boardPadding;
					y -= boardPadding;
					pot5.push([x+1,y-1]);
					x += boardPadding;
					y += boardPadding;
					}
					
				}
			}
			else{
				white3Runs+=1;
				if(!board[x-3][y+3].played && (x-3 > 0+ boardPadding) && (y+3 < rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x-3,y+3]);
				x += boardPadding;
				y += boardPadding;
				}
				if(!board[x-1][y+1].played && (x+1 > 0+ boardPadding) && (y-1 <= rows+ boardPadding)){
				x -= boardPadding;
				y -= boardPadding;
				pot4.push([x-1,y+1]);
				x += boardPadding;
				y += boardPadding;
				}
			}
		}
		
	})
	pieces.forEach(element =>{
		let x = element.x + boardPadding;
		let y = element.y + boardPadding;
		for(let i=-1;i<2;i++){
			for(let j=-1;j<2;j++){
				if((!board[x+i][y+j].played) && (x+i > 0+boardPadding) && (x+i <= rows+boardPadding) && (y+j > 0+boardPadding) && (y+j <= rows+boardPadding)){
					potAdj.push([x+i-boardPadding,y+j-boardPadding])
				}
			}
		}
	});
	whitePotential.push(pot5);
	whitePotential.push(pot4);
	shuffle(potAdj);
	whitePotential.push(potAdj);
	return([[white3Runs,white4Runs,white5Runs],whitePotential]);
}
function drawBoard(board, size, rows, pieces){//this function draws the board and pieces, this is the main display function
		canvas.style.background = colors[(colori+2)%3];
		board.beginPath();
		board.clearRect(0,0,size,size)
		for(let x = 1;x<=rows;x+=1){
			board.moveTo(0,x*(size/rows))
			board.lineTo(size,x*(size/rows))
			board.stroke();
			board.moveTo(x*(size/rows),0)
			board.lineTo(x*(size/rows),size)
			board.stroke();		
		}
		//console.log("Board drawn.")
		//console.log(pieces[0].length)
		pieces[0].forEach(element=>{
		//alert(test)
		x = element.x;
		y = element.y;
		//console.log(x + ", " + y)
	
		context.beginPath();
		context.arc((x)*(size/rows),(y)*(size/rows),((size/rows)/2)-1,0,2*Math.PI);
		context.fillStyle = colors[colori]; //make this selectable later
		context.fill();
		context.font ="24px Arial";
		context.fillStyle = colors[(colori+1)%3];
		let offset = element.turn.toString().length*6;
		context.fillText(element.turn,(x)*(size/rows)-offset,(y)*(size/rows)+6);
		

		})
		
		pieces[1].forEach(element=>{
		x = element.x;
		y = element.y;
		context.beginPath();
		context.arc((x)*(size/rows),(y)*(size/rows),((size/rows)/2)-1,0,2*Math.PI);
		context.fillStyle = colors[(colori+1)%3]; //make this selectable later
		context.fill();
		context.font = "24px Arial";
		context.fillStyle = colors[colori];
		let offset = element.turn.toString().length*6;
		context.fillText(element.turn,(x)*(size/rows)-offset,(y)*(size/rows)+6);
		
		})
		
		//console.log("drawBoard completed")
	}
function place(e, NewGame){//this function grabs the mouse pos and derives figures out what part of the canvas was clicked, then returns plays at those coordinate
	try{
		let size = NewGame.size
		let rows = NewGame.rows
		var pos = getMousePos(canvas, e);
		posx = pos.x; //objective x and y within the canvas	
		posy = pos.y;
		coordx = Math.floor(Math.roundTo(posx,(size/rows))/(size/rows)); //posx rounded down to the nearest multiple of cell size divided by cell size, this gives the coordinate cell
		coordy = Math.floor(Math.roundTo(posy,(size/rows))/(size/rows));
		if((coordx < rows) && (coordy < rows)){
			NewGame.play(coordx,coordy);
			console.log("piece " + (NewGame.turn-1) +" played at: " + coordx + ", " + coordy);
		}
	}
	catch(x){
		//console.log(x)
	}
}
function shuffle(array) { //this is a just an array shuffler function
  let currentIndex = array.length,  randomIndex;

  // While there remain elements to shuffle...
  while (currentIndex != 0) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    // And swap it with the current element.
    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex], array[currentIndex]];
  }

  return array;
}
function timer() { //this function updates the timer and returns the number of minutes and second the game has gone on for
	var endTime = performance.now();
	var timeDiff = endTime - beginTime;
	let m = Math.floor((timeDiff/1000)/60);
	let s = Math.floor((timeDiff/1000)%60);
	let mp = checkTime(m);
	let sp = checkTime(s);
	document.getElementById('clock').innerHTML = mp + ":" + sp;
	if(!gameOver){
		setTimeout(timer, 1000);
	}
	else{
		return[m,s]
	}
}
function checkTime(i) {//this is aultility function for clock functionality
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
function checkTime(i) {// so is this
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
function draw(e) {//deprecated utility/testing function
    var pos = getMousePos(canvas, e);
    posx = pos.x;
    posy = pos.y;
    context.fillStyle = "#000000";
    context.fillRect(posx, posy, 1, 1);
}
//window.addEventListener('mousemove', draw, false);
function getMousePos(canvas, evt) {//this is a utility function for determining mouse pos
    var rect = canvas.getBoundingClientRect();
    return {
        x: (evt.clientX - rect.left) / (rect.right - rect.left) * canvas.width,
        y: (evt.clientY - rect.top) / (rect.bottom - rect.top) * canvas.height
    };
}
if(typeof Math.roundDownTo === "undefined") {//rounding function
Math.roundDownTo = function(num, step) {
return Math.floor((num / step)) * step;
}
}
if(typeof Math.roundTo === "undefined") {//rounding function also
Math.roundTo = function(num, step) {
return Math.floor((num / step) + 0.5) * step;
}
}
</script>
