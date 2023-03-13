const path = require('path');
const Generator = require('@asyncapi/generator')
const generator = new Generator('@asyncapi/html-template', path.resolve(__dirname, '../async'),
    {
        templateParams: {
            singleFile: true
        }
    });

try {
    generator.generateFromFile(path.resolve(__dirname, '../async/asyncapi.yaml')).then(function () {
        console.log('Done!');
    });
} catch (e) {
    console.error(e);
}
