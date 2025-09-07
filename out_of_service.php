<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>BRB SRYYYYY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/globals.css" />
    <style>
        .title {
            position: absolute;
            width: 100%;
            padding: 1.5625rem 3.125rem;
        }

        .maintenance {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 40px;
            padding: 40px;
            color: white;
            max-width: 1200px;
            margin: 0 auto;
        }

        .text-side {
            max-width: 500px;
        }

        .text-side h2 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: left;
        }

        .text-side p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        @media only screen and (max-width: 1000px) {

            .wrapper {
                margin-top: 5rem;
            }

            .title {
                position: relative;
                text-align: center;
                padding: 1rem;
                font-size: 1.5rem;
            }

            .maintenance {
                flex-direction: column;
                padding-top: 0;
                /* żeby tekst nie był zasłonięty */
            }

            .text-side {
                max-width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <main>
        <h1 class="logo">DeckHeaven</h1>
        <div class="wrapper">
            <div class="maintenance">
                <div class="text-side">
                    <h2>⚡ We’re Under Maintenance – But Don’t Worry, It’s Just a Short Tap! ⚡</h2>
                    <p>
                        Uh-oh! It looks like our website has been exiled until the end of the turn.

                    </p>
                    <p>
                        While we resolve these technical combat steps, please hold onto your

                    </p>
                    <p>
                        Thanks for your patience—may your draws be smooth, and your land drops be perfect!
                    </p>
                </div>
                <div class="underglow-container">
                    <img src="./public/assets/cards/duke.svg" style="width: 300%" />

                </div>
            </div>
        </div>
        <div class="underglow"></div>
    </main>

</body>

</html>