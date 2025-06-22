import os
import requests
def send_simple_message():
  	return requests.post(
  		"https://api.mailgun.net/v3/sandboxf30d39fc762a4c7f8917e56ca60c7d3b.mailgun.org/messages",
  		auth=("api", os.getenv('API_KEY', 'API_KEY')),
  		data={"from": "Mailgun Sandbox <postmaster@sandboxf30d39fc762a4c7f8917e56ca60c7d3b.mailgun.org>",
			"to": "Ahamed ilyaas <mrrilyaas@gmail.com>",
  			"subject": "Hello Ahamed ilyaas",
  			"text": "Congratulations Ahamed ilyaas, you just sent an email with Mailgun! You are truly awesome!"})