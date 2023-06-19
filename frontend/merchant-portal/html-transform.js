const { getMPEntryPointsMap } = require("./entry-points");

module.exports = async (targetOptions, indexHtml) => {
    const entryPointsMap = await getMPEntryPointsMap();
    const entryNames = Object.keys(entryPointsMap);

    return entryNames.reduce((html, entryName) => {
        entryTag = createScriptTag(entryName + '.js');
        return insertTextAt(entryTag, html.indexOf("</body>"), html);
    }, indexHtml);
};

function createScriptTag(src) {
    return `<script src="${src}"></script>`;
}

function insertTextAt(text, idx, fullText) {
    return `${fullText.slice(0, idx)}${text}${fullText.slice(idx)}`;
}
