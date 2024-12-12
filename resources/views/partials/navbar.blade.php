<head>
    <style>
        /* Base Styles */
        .navbar-eventwise {
            background-color: #ffffff;
            color: #333;
            padding: 1rem 2rem;
            border-bottom: 1px solid #fef4d3;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center; /* Center the content */
            align-items: center;
        }

        .navbar-eventwise .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center; /* Center the logo */
        }

        .navbar-eventwise .navbar-brand img {
            margin-right: 0; /* No space between logo and text */
            width: 110px;  /* Increased the logo size */
            height: 110px; /* Increased the logo size */
        }

        /* Hover Effect */
        .navbar-eventwise .navbar-brand:hover {
            color: #F7DC6F; /* Yellow color on hover */
            text-decoration: none;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .navbar-eventwise {
                padding: 0.5rem;
            }

            .navbar-eventwise .navbar-brand img {
                width: 40px; /* Adjusted logo size for medium screens */
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .navbar-eventwise {
                padding: 0.2rem;
            }

            .navbar-eventwise .navbar-brand img {
                width: 35px; /* Adjusted logo size for small screens */
                height: 35px;
            }
        }
    </style>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-eventwise">
        <div class="container">
            <!-- Only the Logo -->
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logoWhite.png') }}" alt="EventWise Logo">
            </a>
        </div>
    </nav>
</body>
