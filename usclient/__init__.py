# -*- coding: utf-8 -*-

"""
    Ubersmith Client API calls
    ~~~~~~~~~~~~~~~~~~~~~~~~~~

    :license    Public Domain
"""
import os
import requests
import simplejson as json

class USClient():
    def __init__(self, version=1, inputfile=None, username=None, password=None):
        self.APIURL = "http://hiringapi.dev.voxel.net"
        self.APIURL = self.APIURL + "/v%s/" % version
        self.inputfile = inputfile
        self.APIVERSION = version

        self.UserName = username
        self.Password = password

        if version == 2:
            if not (username and password):
                raise Exception("Version 2 call requires username and password")
            
            self.authenticate()


    def authenticate(self, username=None, password=None):
        # Authentication must happen on the v2 API only
        self.APIURL = "http://hiringapi.dev.voxel.net/v2/"
        from nose.tools import set_trace; set_trace()
        resp = requests.get(self.APIURL + "auth?user=%s&pass=%s" % (username or self.UserName, password or self.Password))
        data = json.loads(resp.text)
        self.AUTHTOKEN = data.get('token')


    def run_script(self, scriptfile=None):
        if not scriptfile:
            scriptfile = self.inputfile

        with open(scriptfile) as f:
            for line in f:
                method = None
                key = None
                value = None
                if line.startswith("#"): continue
                script_args = line.split()
                try:
                    method = script_args[0] 
                    key = script_args[1]
                    value = script_args[2]
                except IndexError, e:
                    # We can ignore out-of-range since we're trying to parse up to 3 params
                    pass
                except Exception, e:
                    raise

                
                resp = self.execute_command(method, key, value)
                print resp


    def execute_command(self, method=None, key=None, value=None):
        resp = None
        if method.startswith("#"): 
            return

        if method == "auth":
            resp = self.authenticate(username=key, password=value)
        elif method == "get":
            resp = self.getter(key=key)
        elif method == "set":
            self.setter(key=key, value=value)
        elif method == "list":
            resp = self.lister()
        elif method == "delete":
            resp = self.deleter(key=key)

        print "Command: %s" % " ".join([x for x in [method, key, value] if x is not None])
        return resp
            

    def load_config(self):
        return "load configs"


    # Response handling decorator
    def responder(func):
        """Decorator taking an HTTP response and returning some useful text
        """
        def decorated_function(*args, **kwargs):
            resp = func(*args, **kwargs)
            data = json.loads(resp.text)

            if resp.status_code != 200:
                return "error %s %s " % (resp.status_code, data.get('msg'))
            else:
                key = None
                if args[0] or kwargs.get('key'):
                    key = data.get(kwargs.get('key'))
                return " ".join([x for x in [data.get('status'), key] if x is not None]) 

        return decorated_function


    @responder
    def getter(self, key=None):
        url = self.APIURL + "key?key=%s" % key
        if self.APIVERSION == 2:
            url = url + "&token=%s" % self.AUTHTOKEN

        resp = requests.get(url)
        return resp

    @responder
    def setter(self, key=None, value=None):
        url = self.APIURL + "key?key=%s&value=%s" % (key, value)
        if self.APIVERSION == 2:
            url = url + "&token=%s" % self.AUTHTOKEN
        resp = requests.put(url)
        return resp

    @responder
    def lister(self):
        url = self.APIURL + "list" 
        if self.APIVERSION == 2:
            url = url + "&token=%s" % self.AUTHTOKEN
        resp = requests.get(url)
        return resp

    @responder
    def deleter(self, key=None):
        url = self.APIURL + "delete"
        if self.APIVERSION == 2:
            url = url + "&token=%s" % self.AUTHTOKEN
        resp = requests.delete(url)
        return resp
        


