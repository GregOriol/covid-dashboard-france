#!/bin/sh

php get.php
cp index.html docs/

mkdir -p docs/node_modules/normalize.css/
cp node_modules/normalize.css/normalize.css docs/node_modules/normalize.css/normalize.css
mkdir -p docs/node_modules/c3/
cp node_modules/c3/c3.min.css docs/node_modules/c3/c3.min.css

mkdir -p docs/node_modules/d3/dist/
cp node_modules/d3/dist/d3.min.js docs/node_modules/d3/dist/d3.min.js
mkdir -p docs/node_modules/c3/
cp node_modules/c3/c3.min.js docs/node_modules/c3/c3.min.js
mkdir -p docs/node_modules/tinycolor2/dist/
cp node_modules/tinycolor2/dist/tinycolor-min.js docs/node_modules/tinycolor2/dist/tinycolor-min.js

cp index.css docs/
cp index.js docs/
