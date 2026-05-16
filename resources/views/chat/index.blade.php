<!DOCTYPE html>
<html>

<head>

    <title>Chat</title>

    @vite(['resources/js/app.js'])

    <meta name="csrf-token"
        content="{{ csrf_token() }}">


<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:sans-serif;
}


body{
background:#111b21;
height:100vh;
overflow:hidden;
}


.chat-container{
display:flex;
flex-direction:column;
height:100vh;
}


.chat-header{

background:#202c33;
padding:12px 16px;
display:flex;
align-items:center;
gap:12px;
color:white;

}


.avatar{

width:45px;
height:45px;
border-radius:50%;
background:#25d366;
display:flex;
align-items:center;
justify-content:center;
font-size:20px;
font-weight:bold;

}


.user-status{

font-size:13px;
color:#8696a0;

}



.user-select{

padding:8px;
background:#2a3942;
border:none;
color:white;
border-radius:8px;
margin-left:auto;

}



.chat-body{

flex:1;
overflow:auto;
padding:20px;
background:#0b141a;

}



.message{

max-width:80%;
padding:10px;
margin-bottom:12px;
border-radius:10px;

word-break:break-word;

}


.sent{

background:#005c4b;
margin-left:auto;
color:white;

}



.received{

background:#202c33;
color:white;

}



.meta{

font-size:11px;
opacity:.7;
text-align:right;
margin-top:5px;

}



.chat-input{

display:flex;
padding:10px;
background:#202c33;
gap:10px;

}



.chat-input input{

flex:1;
padding:12px;
border:none;
outline:none;
background:#2a3942;
color:white;
border-radius:25px;

}



.chat-input button{

padding:12px 18px;
border:none;
background:#00a884;
border-radius:50%;
color:white;
cursor:pointer;

}



@media(max-width:768px){

.message{

max-width:90%;

}

}

</style>

</head>

<body>


<div class="chat-container">


<div class="chat-header">


<div class="avatar"

id="avatar">

?

</div>



<div>

<div

id="chat-user">

Select User

</div>



<div

id="typing"

class="user-status">

online

</div>


</div>



<select

id="receiver"

class="user-select">


@foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)


<option

value="{{ $user->id }}"

data-name="{{ $user->name }}">

{{ $user->name }}

</option>


@endforeach


</select>



</div>




<div

id="messages"

class="chat-body">

</div>





<div class="chat-input">


<input

id="message"

placeholder="Type a message">



<button

onclick="sendMessage()">

➤

</button>



</div>


</div>






<script>


let receiver=

document.getElementById(

'receiver'

);



let receiverId=

receiver.value;




function updateHeader()
{

let name=

receiver.options[

receiver.selectedIndex

]

.dataset.name;



document.getElementById(

'chat-user'

)

.innerHTML=

name;



document.getElementById(

'avatar'

)

.innerHTML=

name.charAt(0)

.toUpperCase();


}



updateHeader();



receiver.addEventListener(

'change',

()=>{


receiverId=

receiver.value;


updateHeader();


});






function sendMessage()
{

let msg=

message.value;



if(!msg)

return;




fetch(

'/send',

{

method:'POST',


headers:{

'Content-Type':

'application/json',


'X-CSRF-TOKEN':

document.querySelector(

'meta[name=csrf-token]'

).content

},


body:

JSON.stringify({

message:msg,

receiver_id:

receiverId

})

}


)

.then(

r=>r.json()

)

.then(

data=>{


messages.innerHTML +=

`

<div class="message sent">

${data.message}


<div class="meta">

${data.created_at ?? ''}

✓

</div>

</div>

`;



message.value='';


scrollBottom();


}


);


}





message.addEventListener(

'input',

()=>{


fetch(

'/typing',

{

method:'POST',


headers:{

'Content-Type':

'application/json',


'X-CSRF-TOKEN':

document.querySelector(

'meta[name=csrf-token]'

).content

},


body:

JSON.stringify({

receiver_id:

receiverId

})

}


);


});






document.addEventListener(

'DOMContentLoaded',

()=>{


window.Echo.private(

'chat.'+

{{ auth()->id() }}

)



.listen(

'.message.sent',

e=>{


messages.innerHTML +=

`

<div class="message received">

${e.message}


<div class="meta">

${e.created_at}

</div>

</div>

`;



scrollBottom();



})





.listen(

'.typing',

()=>{


typing.innerHTML=

'typing...';



setTimeout(

()=>{


typing.innerHTML=

'online';


},

1000

);


});




});





function scrollBottom()
{

messages.scrollTop=

messages.scrollHeight;

}



message.addEventListener(

'keypress',

e=>{


if(

e.key==='Enter'

)

sendMessage();


});


</script>


</body>

</html>