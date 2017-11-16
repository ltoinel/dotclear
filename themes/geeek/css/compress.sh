#!/bin/bash
sass main.sass  main.css
cat ext/shCore.css ext/shThemeDefault.css main.css > geeek.css
sqwish geeek.css
