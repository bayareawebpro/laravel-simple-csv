
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:BayAreaWebPro" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="BayAreaWebPro.html">BayAreaWebPro</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:BayAreaWebPro_SimpleCsv" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="BayAreaWebPro/SimpleCsv.html">SimpleCsv</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:BayAreaWebPro_SimpleCsv_SimpleCsv" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="BayAreaWebPro/SimpleCsv/SimpleCsv.html">SimpleCsv</a>                    </div>                </li>                            <li data-name="class:BayAreaWebPro_SimpleCsv_SimpleCsvExporter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html">SimpleCsvExporter</a>                    </div>                </li>                            <li data-name="class:BayAreaWebPro_SimpleCsv_SimpleCsvFacade" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="BayAreaWebPro/SimpleCsv/SimpleCsvFacade.html">SimpleCsvFacade</a>                    </div>                </li>                            <li data-name="class:BayAreaWebPro_SimpleCsv_SimpleCsvImporter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="BayAreaWebPro/SimpleCsv/SimpleCsvImporter.html">SimpleCsvImporter</a>                    </div>                </li>                            <li data-name="class:BayAreaWebPro_SimpleCsv_SimpleCsvServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html">SimpleCsvServiceProvider</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "BayAreaWebPro.html", "name": "BayAreaWebPro", "doc": "Namespace BayAreaWebPro"},{"type": "Namespace", "link": "BayAreaWebPro/SimpleCsv.html", "name": "BayAreaWebPro\\SimpleCsv", "doc": "Namespace BayAreaWebPro\\SimpleCsv"},
            
            {"type": "Class", "fromName": "BayAreaWebPro\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsv.html", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsv", "doc": "&quot;The SimpleCsv Facade&quot;"},
                                                        {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsv.html#method___construct", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsv::__construct", "doc": "&quot;SimpleCsv constructor.&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsv.html#method_import", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsv::import", "doc": "&quot;Import the CSV to a new Collection&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsv.html#method_export", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsv::export", "doc": "&quot;Import the CSV to a new Collection&quot;"},
            
            {"type": "Class", "fromName": "BayAreaWebPro\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter", "doc": "&quot;The SimpleCsv Exporter&quot;"},
                                                        {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html#method___construct", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter::__construct", "doc": "&quot;Importer constructor.&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html#method_generateLines", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter::generateLines", "doc": "&quot;Read Lines&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html#method_save", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter::save", "doc": "&quot;Save the CSV File to Disk&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvExporter.html#method_download", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvExporter::download", "doc": "&quot;Export CSV File to Download Response&quot;"},
            
            {"type": "Class", "fromName": "BayAreaWebPro\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvFacade.html", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvFacade", "doc": "&quot;The SimpleCsv Service Facade&quot;"},
                                                        {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvFacade", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvFacade.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvFacade.html#method_getFacadeAccessor", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvFacade::getFacadeAccessor", "doc": "&quot;Get the registered name of the component.&quot;"},
            
            {"type": "Class", "fromName": "BayAreaWebPro\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvImporter.html", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvImporter", "doc": "&quot;The SimpleCsv Importer&quot;"},
                                                        {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvImporter", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvImporter.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvImporter.html#method___construct", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvImporter::__construct", "doc": "&quot;Importer constructor.&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvImporter", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvImporter.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvImporter.html#method_import", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvImporter::import", "doc": "&quot;Import the CSV to a new Collection&quot;"},
            
            {"type": "Class", "fromName": "BayAreaWebPro\\SimpleCsv", "fromLink": "BayAreaWebPro/SimpleCsv.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider", "doc": "&quot;The SimpleCsv Service Provider&quot;"},
                                                        {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html#method_register", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider::register", "doc": "&quot;Register any application services.&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html#method_boot", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider::boot", "doc": "&quot;Bootstrap any application services.&quot;"},
                    {"type": "Method", "fromName": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider", "fromLink": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html", "link": "BayAreaWebPro/SimpleCsv/SimpleCsvServiceProvider.html#method_provides", "name": "BayAreaWebPro\\SimpleCsv\\SimpleCsvServiceProvider::provides", "doc": "&quot;The services provided.&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


