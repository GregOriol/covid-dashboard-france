#!/bin/sh

php get.php
cat index.html | sed -E 's#"node_modules/[^"]*/([^"]*)"#"\1"#g' > docs/index.html

cp node_modules/normalize.css/normalize.css docs/normalize.css
cp node_modules/c3/c3.min.css docs/c3.min.css

cp node_modules/d3/dist/d3.min.js docs/d3.min.js
cp node_modules/c3/c3.min.js docs/c3.min.js
cp node_modules/tinycolor2/dist/tinycolor-min.js docs/tinycolor-min.js

cp index.css docs/
cp index.js docs/
