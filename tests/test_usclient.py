# -*- coding: utf-8 -*-

import unittest
from usclient import USClient

class BaseUsClientTestsv1(unittest.TestCase):
    def setUp(self):
        self.usc = USClient(version=1)
            
class BaseUsClientTestsv2(unittest.TestCase):
    def setUp(self):
        self.usc = USClient(version=2, username="test", password="testpw")
            

class UsClientPositiveTestsv1(BaseUsClientTestsv1):

    def test_full_sequence(self):
        self.assertEqual(self.usc.setter(key="mykey", value="myvalue"), "ok")
        self.assertEqual(self.usc.getter(key="mykey"), "ok myvalue")

        resp = self.usc.lister()
        self.assertTrue(resp.startswith("ok"), resp)
        self.assertTrue(len(resp.split()) > 0)

        resp = self.usc.deleter(key="mykey")
        self.assertTrue(resp.startswith("ok"), resp)

        self.assertEqual(self.usc.getter(key="mykey"), "error 404 unknown key mykey")

class UsClientPositiveTestsv2(BaseUsClientTestsv2):

    def test_full_sequence(self):
        self.assertEqual(self.usc.setter(key="mykey", value="myvalue"), "ok")
        self.assertEqual(self.usc.getter(key="mykey"), "ok myvalue")

        resp = self.usc.lister()
        self.assertTrue(resp.startswith("ok"), resp)

        resp = self.usc.deleter(key="mykey")
        self.assertTrue(resp.startswith("ok"), resp)

        self.assertEqual(self.usc.getter(key="mykey"), "error 404 unknown key mykey")


if __name__ == '__main__':
    unittest.main()
