<!DOCTYPE html>
<html>
<head>
    <title>Thanks for your order!</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #242d60;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto',
            'Helvetica Neue', 'Ubuntu', sans-serif;
            height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        section {
            background: #ffffff;
            display: flex;
            flex-direction: column;
            width: 400px;
            height: 112px;
            border-radius: 6px;
            justify-content: space-between;
            margin: 10px;
        }
        .product {
            display: flex;
        }
        .description {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        p {
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: -0.154px;
            color: #242d60;
            height: 100%;
            width: 100%;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }
        svg {
            border-radius: 6px;
            margin: 10px;
            width: 54px;
            height: 57px;
        }
        h3,
        h5 {
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: -0.154px;
            color: #242d60;
            margin: 0;
        }
        h5 {
            opacity: 0.5;
        }
        a {
            text-decoration: none;
            color: white;
        }
        #checkout-and-portal-button {
            height: 36px;
            background: #556cd6;
            color: white;
            width: 100%;
            font-size: 14px;
            border: 0;
            font-weight: 500;
            cursor: pointer;
            letter-spacing: 0.6;
            border-radius: 0 0 6px 6px;
            transition: all 0.2s ease;
            box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
        }
        #checkout-and-portal-button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
<section>
    <div class="product Box-root">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="16px" viewBox="0 0 14 16" version="1.1">
            <defs/>
            <g id="Flow" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="0-Default" transform="translate(-121.000000, -40.000000)" fill="#E184DF">
                    <path d="M127,50 L126,50 C123.238576,50 121,47.7614237 121,45 C121,42.2385763 123.238576,40 126,40 L135,40 L135,56 L133,56 L133,42 L129,42 L129,56 L127,56 L127,50 Z M127,48 L127,42 L126,42 C124.343146,42 123,43.3431458 123,45 C123,46.6568542 124.343146,48 126,48 L127,48 Z" id="Pilcrow"/>
                </g>
            </g>
        </svg>
        <div class="description Box-root">
            <h3>Subscription to Starter plan successful!</h3>
        </div>
    </div>
</section>
</body>
</html>
