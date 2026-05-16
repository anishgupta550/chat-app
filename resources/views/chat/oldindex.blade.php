<!DOCTYPE html>
<html>

<head>

    <title>Chat</title>

    @vite(['resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>


    <select id="receiver">

        @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)

            <option value="{{ $user->id }}">

                {{ $user->name }}

            </option>

        @endforeach

    </select>



    <input id="message" placeholder="Type message">


    <button onclick="sendMessage()">

        Send

    </button>



    <hr>


    <div id="messages">

    </div>




    <script>


        function sendMessage() {

            let message =

                document.getElementById(

                    'message'

                ).value;



            let receiverId =

                document.getElementById(

                    'receiver'

                ).value;




            fetch(

                '/send',

                {

                    method: 'POST',


                    headers: {

                        'Content-Type':

                            'application/json',


                        'X-CSRF-TOKEN':

                            document.querySelector(

                                'meta[name="csrf-token"]'

                            ).content

                    },


                    body:

                        JSON.stringify({

                            message:

                                message,

                            receiver_id:

                                receiverId

                        })

                }

            )

                .then(

                    res => res.json()

                )

                .then(

                    data => {


                        document.getElementById(

                            'messages'

                        )

                            .innerHTML +=

                            `

<div>

<b>

Me

</b>

:

${data.message}

</div>

`;



                        document.getElementById(

                            'message'

                        ).value = '';



                    });


        }





        document.addEventListener(

            'DOMContentLoaded',

            () => {


                window.Echo.private(

                    'chat.' +

{{ auth()->id() }}

)


                    .listen(

                        '.message.sent',

                        (e) => {


                            document.getElementById(

                                'messages'

                            )

                                .innerHTML +=

                                `

<div>

<b>

User ${e.sender_id}

</b>

:

${e.message}

</div>

`;


                            console.log(

                                'received',

                                e

                            );


                        }

                    );



            });


    </script>


</body>

</html>