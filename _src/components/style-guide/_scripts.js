window.addEventListener('DOMContentLoaded', () => {
    /*
     Check if the stylesheet is internal or hosted on the current domain.
     If it isn't, attempting to access sheet.cssRules will throw a cross origin error.
     See https://developer.mozilla.org/en-US/docs/Web/API/CSSStyleSheet#Notes
    */
    const isSameDomain = (styleSheet) => {
        // Internal style blocks won't have an href value
        if (!styleSheet.href) {
            return true;
        }

        return styleSheet.href.indexOf(window.location.origin) === 0;
    };

    /*
     Determine if the given rule is a CSSStyleRule
     See: https://developer.mozilla.org/en-US/docs/Web/API/CSSRule#Type_constants
    */
    const isStyleRule = (rule) => rule.type === 1;

    /**
     * Get all custom properties on a page
     * @return array<array[string, string]>
     * ex; [["--color-accent", "#b9f500"], ["--color-text", "#252525"], ...]
     */
    const getCSSCustomPropIndex = () =>
        // styleSheets is array-like, so we convert it to an array.
        // Filter out any stylesheets not on this domain
        [...document.styleSheets].filter(isSameDomain).reduce(
            (finalArr, sheet) =>
                finalArr.concat(
                    // cssRules is array-like, so we convert it to an array
                    [...sheet.cssRules].filter(isStyleRule).reduce((propValArr, rule) => {
                        const props = [...rule.style]
                            .map((propName) => [
                                propName.trim(),
                                rule.style.getPropertyValue(propName).trim(),
                            ])
                            // Discard any props that don't start with "--". Custom props are required to.
                            .filter(([propName]) => propName.indexOf('--') === 0);

                        return [...propValArr, ...props];
                    }, [])
                ),
            []
        );

    let cssCustomPropIndex = getCSSCustomPropIndex();

    // Remove all duplicates from the array
    cssCustomPropIndex = Array.from(new Set(cssCustomPropIndex));

    // // Add Property title and input to the editor pane
    // document.querySelector(".theme-editor__props").innerHTML = cssCustomPropIndex.reduce(
    //     (str, [prop, val]) => `
    //         ${str}<div class="prop">
    //             <h5 class="prop__name">${prop}</h5>
    //             <input name="${prop}" class="prop__input" value="${val}" />
    //         </div>`,
    //     ""
    // );

    // Add color swatches to the DOM
    document.querySelector('.colors').innerHTML = cssCustomPropIndex
        .filter(([propName]) => propName.indexOf('--color') === 0)
        .filter(([propName]) => !propName.includes('--hsl'))
        .reduce(
            (str, [prop, val]) => `
            ${str}<li class="color">
                <b class="color__swatch" style="--color: ${val}"></b>
                <div class="color__details">
                <input value="${prop}" readonly />
                <input name="${prop}" value="${val}" readonly />
                </div>
            </li>`,
            ''
        );

    const propInputs = document.querySelectorAll('.prop__input');

    propInputs.forEach((i) => {
        i.addEventListener('input', () => {
            document.documentElement.style.setProperty(this.name, this.value);
            localStorage.setItem(this.name, this.value);
        });
    });
});
