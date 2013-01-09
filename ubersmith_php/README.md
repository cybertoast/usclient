# Overview

    mkvirtualenv usclient
    pip install -r requirements.txt

    python main.py -h 
    python main.py -i input.v1.example
    python main.py -i input.v2.example


# Testing

    nosetests tests/test_usclient.py

# Troubleshooting
    
* The DELETE resource does not appear to exist at all
* The server is quite unstable - seems to return 503 quite regularly
