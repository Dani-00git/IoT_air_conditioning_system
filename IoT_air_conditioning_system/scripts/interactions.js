class Building{
    constructor() {
        this.rooms = new Array();
        this.avgTemp;
        this.freeIN = new Array();
        this.freeOUT = new Array();
        for(let i = 22; i<37; i++){
            this.freeIN.push(i);
        }
        for(let i = 37; i<52; i++){
            this.freeOUT.push(i);
        }
    }
    addRoom(numSens, numCond, name){
        if(numSens>this.freeIN.length && numCond>this.freeOUT.length){
            return console.log("exeeded");
        }
        var sensPIN = new Array();
        var condPIN = new Array();
        
        for(let i = 0; i<numSens; i++){
            sensPIN.push(this.freeIN.pop(0));
        }
        for(let i = 0; i<numCond; i++){
            condPIN.push(this.freeOUT.pop(0));
        }
        
        let newRoom = new Room(name, sensPIN, condPIN);
        this.rooms.push(newRoom);
        var request = $.ajax({
            url: "192.168.0.250:8888",
            type: "GET",
            data: {"action" : "addRoom",
                   "name" : name,
                   "numSens" : numSens,
                   "numCond" : numCond},
            dataType: "json",
        });  
        for(let i = 0; i<sensPIN.length; i++){
            var request = $.ajax({
                url: "192.168.0.250:8888",
                type: "GET",
                data: {"action" : "setPIN",
                       "room" : name,
                       "PIN" : sensPIN[i]},
                dataType: "json",
            });  
        }
        
    }
    removeRoom(room){
        this.rooms.pop(room);
        this.freeIN.concat(room.inPINS);
        this.freeOUT.concat(room.outPINS);
        var request = $.ajax({
            url: "192.168.0.250:8888",
            type: "GET",
            data: {"action" : "removeRoom",
                   "room" : room.name},
            dataType: "json",
        });  
    }
    
    calcAvgTemp(){
        let sum = 0;
        for (let i = 0; i < this.rooms.length; i++) {
            sum += this.rooms[i].temp;
        }
        this.avgTemp = sum/this.rooms.length;
    }
}

class Room{
    constructor(name, inPIN, outPIN) {
        this.name = name;
        this.tempTarget;
        this.temp;
        this.inPINS = inPIN;
        this.outPINS = outPIN;
        this.switch = 0;
    }
    calcTemp(){
        let sum = 0;
        for (let i = 0; i < this.inPINS.length; i++) {
            var request = $.ajax({
                url: "192.168.0.250:8888",
                type: "GET",
                data: {"action" : "getReading",
                       "PIN" : this.inPINS[i]},
                dataType: "json",
            });

            request.done(function(data) {
                sum += data["temp"];
            });
        }
        this.temp = sum/this.outPINS.length;
    }
    setCommands(){
        if(temp < tempTarget) {this.switch = 1;}
        if(temp >= tempTarget) {this.switch = 0;}
        for(let i = 0; i < this.outPINS.length; i++){
            var request = $.ajax({
            url: "192.168.0.250:8888",
            type: "GET",
            data: {"action" : "setCommand",
                   "PIN" : this.outPINS[i],
                   "state" : this.switch},
            dataType: "json",
            });
        }
    }
}

var i = 0;
var k;

document.addEventListener(
   'DOMContentLoaded',
   function() {
       console.log('DOMContentLoaded');
       var addRoom;
       let b = new Building();
       document.getElementById("plus").addEventListener(
           'click',
           function() {
                    document.getElementById("temptot").innerHTML++;
           });
       document.getElementById("minus").addEventListener(
           'click',
           function() {
                    document.getElementById("temptot").innerHTML--;
           });
       document.getElementById("confirm").addEventListener(
           'click',
           function() {
                    document.getElementById("temptot").innerHTML;
           });
       
       
       addRoom = document.querySelector("#addroom");
       addRoom.addEventListener("click", addNewRoom); 
       
}
       
);

function addNewRoom() {
    i++;
    
    //compileForm();
    var form = document.getElementsByClassName("setRooms");
    form[0].style.display = "block";
    
    const newDiv = document.createElement("div");
    document.body.appendChild(newDiv);
    newDiv.className = "rooms";
    //newDiv.setAttribute("id", "stanza"+i);
    
    var newH1 = document.createElement("h1");
    newDiv.appendChild(newH1);
    newH1.textContent="Stanza " + i;
    
    const cancel = document.createElement("button");
    cancel.className = "cancelButton";
    //cancel.setAttribute("id","x"+i);
    newDiv.appendChild(cancel);
    
    var x = document.createElement("p");
    cancel.appendChild(x);
    x.textContent="X"; 
    
    cancel.addEventListener("click", function() {
        cancel.parentElement.style.display = "none";
    })
    
}
       
function compileForm()   {
    var form = document.createElement("div");
    document.body.appendChild(form);
    form.className = "setRoom";
    
    var list = document.createElement("ul");
    form.appendChild(list);
    
    var li1 = document.createElement("li");
    list.appendChild(li1);
    
    var p1 = document.createElement("p");
    li1.appendChild(p1);
    p1.textContent = "Nome stanza: "
    
    var form1 = document.createElement("form");
    li1.appendChild(form1);
    
    var li2 = document.createElement("li");
    list.appendChild(li2);
    
    var p2 = document.createElement("p");
    li2.appendChild(p2);
    p2.textContent = "Metri quadri: "
    
    var form2 = document.createElement("form");
    li2.appendChild(form2);
    
    var li3 = document.createElement("li");
    list.appendChild(li3);
    
    var p3 = document.createElement("p");
    li3.appendChild(p3);
    p3.textContent = "Numero sensori: "
    
    var form3 = document.createElement("form");
    li3.appendChild(form3);
    
    var li4 = document.createElement("li");
    list.appendChild(li4);
    
    var p4 = document.createElement("p");
    li4.appendChild(p4);
    p4.textContent = "Numero climatizzatori: "
    
    var form4 = document.createElement("form");
    li4.appendChild(form4);
} 
