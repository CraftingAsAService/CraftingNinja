const mix = require('laravel-mix');
const fs = require('fs');
const junk = require('junk');

let getFiles = function (dir) {
	// get all 'files' in this directory
	// filter directories
	return fs.readdirSync(dir).filter(file => {
		return fs.statSync(`${dir}/${file}`).isFile();
	}).filter(junk.not);
};

getFiles('resources/js/pages').forEach(function (filepath) {
	mix.js('resources/js/pages/' + filepath, 'public/js/pages');
});

getFiles('resources/scss/pages').forEach(function (filepath) {
	mix.sass('resources/scss/pages/' + filepath, 'public/css/pages');
});

getFiles('resources/scss/themes').forEach(function (filepath) {
	if (filepath.substr(0, 1) != '_')
		mix.sass('resources/scss/themes/' + filepath, 'public/css/themes');
});

mix.sass('resources/scss/app.scss', 'public/css');
mix.sass('resources/scss/alchemists/theme.scss', 'public/css/alchemists');

mix.js('resources/js/app.js', 'public/js')
	.extract(['vue']);

if (mix.inProduction()) {
	mix.version();
}
