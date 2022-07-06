var temperaturaTotale ;
var i = 0;
var k

document.addEventListener(
   'DOMContentLoaded',
   function() {
       console.log('DOMContentLoaded');
       var addRoom;
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

