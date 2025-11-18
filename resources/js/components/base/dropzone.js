document.addEventListener("alpine:init", () => {
    Alpine.directive("dropzone", (el, { expression }, { evaluate }) => {
        if (document.createElement(el.tagName).constructor === HTMLElement)
            return;

        const params = evaluate(expression);
        let options = {
            accept: (file, done) => {
                console.log("Uploaded");
                done();
            },
            ...params,
        };

        if (params.single) {
            options.maxFiles = 1;
        }

        if (params.fileTypes) {
            options.accept = (file, done) => {
                if (params.fileTypes.split("|").indexOf(file.type) === -1) {
                    alert("Error! Files of this type are not accepted");
                    done("Error! Files of this type are not accepted");
                } else {
                    console.log("Uploaded");
                    done();
                }
            };
        }

        let dz = new Dropzone(el, options);

        dz.on("maxfilesexceeded", (file) => {
            alert("No more files please!");
        });
    });
});
