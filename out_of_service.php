<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>BRB SRYYYYY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/globals.css" />
    <style>
        body {
            display: flex;
            height: 100vh;
            width: 100vw;
            background-color: #101010;
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h2 {
            margin: 0 0 0.625rem;
            font-weight: 600;
        }

        p {
            margin: 0 0 1.25rem;
            font-size: 0.875rem;
            color: #aaa;
        }

        label {
            font-size: 0.875rem;
            display: block;
            margin-bottom: 0.375rem;
        }



        .title {
            position: absolute;
            width: 100%;
            padding: 1.5625rem 3.125rem;
        }

        .underglow {
            left: 15%;
            border-radius: 999px;
            background: radial-gradient(circle at 50% 50%,
                    #990497 0%,
                    transparent 70%);
            z-index: -1;
            width: 100vw;
            height: 100vh;
            position: absolute;
        }

        .underglow-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            flex-grow: 1;
            height: 100vh;
            width: 100%;
        }

        .wrapper {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            position: relative;
            width: 100vw;
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

        main {
            display: flex;
            flex-direction: column;
            max-width: 100vw;
            max-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        @media only screen and (max-width: 1000px) {
            .underglow-container {
                display: none;
            }

            .underglow {
                left: 0%;
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
        <h1 class="jq24 title">DeckHeaven</h1>
        <div class="wrapper">
            <div class="maintenance">
                <div class="text-side">
                    <h2>⚡ We’re Under Maintenance – But Don’t Worry, It’s Just a Short Tap! ⚡</h2>
                    <p>
                        Uh-oh! It looks like our website has been exiled until the end of the turn.
                        We’re currently working on some serious deck-building behind the scenes,
                        upgrading our mana base, and making sure there are no counterspells
                        interfering with your experience.
                    </p>
                    <p>
                        While we resolve these technical combat steps, please hold onto your
                        Planeswalker loyalty—we promise this isn’t a permanent removal effect!
                        Check back soon, and we’ll have everything back on the battlefield, ready
                        for an epic game. In the meantime, consider this a short scry into the
                        future of an even better experience!
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