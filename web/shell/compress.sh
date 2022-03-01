cd ~/public_html/pos/web/css &&
    lessc -clean-css site.less site.min.css
    lessc -clean-css print.less print.min.css
    lessc -clean-css core/main/core.less core/core.min.css
    lessc -clean-css core/main/prefix.less core/prefix.min.css