# -*- coding: utf-8 -*-

from optparse import OptionParser
from usclient import USClient

if __name__ == '__main__':

    parser = OptionParser()
    parser.add_option("-i", "--inputfile", help="Input file")
    opts, args = parser.parse_args()

    # Ensure that mandatory options exist
    if not opts.inputfile:
        parser.print_help()
        exit(-1)

    usc = USClient(inputfile=opts.inputfile)
    usc.run_script()
