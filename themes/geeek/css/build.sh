#!/bin/bash
sass ./sass/main.sass  ./sass/main.css
cat ./ext/*/*.css ./sass/main.css > geeek.css
sqwish geeek.css
