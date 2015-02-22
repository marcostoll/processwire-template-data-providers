# ProcessWire Module "Template Data Providers" #

This module offers you separation of concerns by giving you the option to load and prepare necessary data not within a template but within a custom data provider class.

As additional feature a new method `Page::renderChunk()` is provided for all Page instances to load and render contents of a chunk (a.k.a. partial, include, block) file and passing contextual data to it.

## Installing the module ##

Just copy the module files into you `site/modules/` folder or download it via the ModuleManager.  
Your directory structure should look like this:  

site/  
|-> modules/  
|--|-> TemplateDataProviders  
|--|--|-> examples/  
|--|--|-> nw/  
|--|--|-> README.md  
|--|--|-> TemplateDataProviders.module  
 
After deploying the module files go to **Setup/Modules** in your ProcessWire backend. You should find the **Template Data Providers** module in the **Template** section. Hit install and be ready. 

## Configuring the module ##

The **Template Data Providers** module requires only one setting: the path to the data provider classes. 

As a default `dataproviders` is defined. But you may overwrite that with any path you want.  
This path must be given relative to ProcessWire's template path (`site/templates/` per default).

**Attention**: Be sure to create the data providers class path on your server. The module will not do that for you. Access writes for reading files from this directory must be granted to the PHP process as usual.

## Defining a page data provider  ##

First of all: Defining data providers for pages is optional. You may ignore this completely and code your templates as usual whether you have installed this module or not.

Page data providers are meant to load and prepare data for your templates so you won't have to do this within your templates. As a result of that page data providers have to be defined for a template, not a page.

Another example to put a page data provider to good use is form handling. With a page data provider you could process your form data and provide form status information to your template separated from the markup.

Let's assume we have a page using the **home** template and you like to display some data not directly provided as page fields. Then you could to this:

Create a **HomePage.php** file and save it to you data providers class path (e.g. `site/templates/dataproviders/`).  
Insert the following code:  
  
    class HomePage extends \nw\DataProviders\PageDataProvider {
    	
        public function populate() {

            $this->foo = 'bar';       // provides variable $foo to use within the page's template
            $this->page->foo = 'baz'; // provides page member $page->foo to use within the page's template
        } 
    } 

Of course you have access to the global wire() function and therefore the full ProcessWire API at hand.

**Attention**: Be sure to extend from `\nw\dataProviders\PageDataProvider` if you want to create a data provider for a specific template.

### Naming page data providers ###

Class names for page data providers follow this convention: `{CamelCaseTemplateName}Page`  
Just use the camelcase notation of your template's name and add 'Page'. Dashes and underscores are used as separators for camelcasing.  
Some Examples:  
- home            -> class: HomePage | file: HomePage.php  
- search_results  -> class: SearchResultsPage | file: SearchResultsPage.php  
- list-news       -> class: ListNewsPage | file: ListNewsPage.php  

The file name must be named after the class name (case sensitive) and using a .php file extension.

## Defining a chunk data provider ##

Chunks (a.k.a partials, includes, blocks, etc.) are parts of a template, that are stored in separate files mainly to reuse the code throughout multiple templates. Think of a header, footer or sidebar section that should be used website wide.

In default ProcessWire context you literally include (or require) the PHP file containing the code for the chunk which than will be executed. Within the chunk file you have access to the same scope of variables as outside the chunk.

And this can cause multiple problems:  
1. Variable scopes are not separated. So creating, modifying and/or unsetting a variable within a chunk will also affect the outer template scope.  
2. Data can only be passed to the chunk's variable scope by storing it in the template's global variable scope.

By installing the Template Data Providers module you have access to a new page method `Page::renderChunk($chunkFile)` that handles chunk rendering yor you.

Example: 
In our home template we want to include a header chunk. We now could use this code  

    $page->renderChunk('path/to/header.php'); // relative to wire('config')->paths->templates 

***Hint: The file extension is optional. If no extension is given $config->templateExtension (defaults to .php) is used.***

Now that's not much different to the classic way of including chunks. But now the **Template Data Providers** module looks for a `HeaderChunk` data provider and invokes it's `populate()` method to provide data for the header chunk that only lives within the chunk's scope.

In addition to this  you may pass contextual data to the chunks variable scope by passing additonal arguments:

    $page->renderChunk('path/to/header.php', $arg1, $arg2); // renderChunk() accepts an arbitrary amount of additional arguments

So for our header chunk we create a **HeaderChunk.php** file and save it to you data providers class path (e.g. `site/templates/dataproviders/`). Insert the following code:  
  
    class HeaderChunk extends \nw\DataProviders\ChunkDataProvider {
    	
        public function populate() {

            $this->foo = 'bar'; // provides variable $foo to use within the chunk            
        } 
    } 

Of course you have access to the global wire() function and therefore the full ProcessWire API at hand.

**Attention**: Be sure to extend from `\nw\dataProviders\ChunkDataProvider` if you want to create a data provider for a specific chunk.

### Naming chunk data providers ###

Class names for chunk data providers follow this convention: `{CamelCaseChunkFileName}Chunk`  
Just use the camelcase notation of your chunk's file name (ignoring path and file extenion) and add 'Chunk'. Dashes and underscores are used as separators for camelcasing.  
Some Examples:  
- header.php     -> class: HeaderChunk | file: HeaderChunk.php  
- news-item.inc  -> class: NewsItemChunk | file: NewsItemChunk.php  
- left_col.twig  -> class: LeftColChunk | file: LeftColChunk.php  

The file name must be named after the class name (case sensitive) and using a .php file extension.

### The generic chunk data provider ###

If you execute a chunk using `Page::renderChunk()` a chunk data provider will be instatianted whether or not you have defined a custom chunk data provider for the chunk to execute. If the **Template Data Providers** does not find a suitable chunk data provider the generic chunk data provider will be executed.

### Passing contextual data to chunk data providers ###

A good usage of passing contextual data to chunks is when you define chunks for displaying list items. For example you have a PageArray to iterate over and want to display each item within the collection by using a chunk.

    $news = $pages->get('/news')->children();
    foreach ($news as $newItem) {
        $page->renderChunk('news-item.php', $newItem);
    }

If the generic chunk data provider is used (no `NewsItemChunk` data provider was found), you have access to a `$context` variable. `$context` is an numeric array containing all the additional arguments passed to `Page::renderChunk()`.

In this case `$context` would have size 1, and you would find your news item page in `$chunk[0]`.

If you create a custom chunk data provider (NewsItemChunk in our example), you may overwrite the method `\nw\DataProviders\ChunkDataProvider::setContext(array $context)` to implement custom handling of contextual data.

By overwriting this method you have two options:  
1. Validating contextual data  
2. Storing contextual data in custom variables

    class NewsItemChunk extends \nw\DataProviders\ChunkDataProvider {
    	
        public function setContext(array $context) {
			
			// validate contextual data
			if (!isset($context[0]) || !($context[0] instanceof Page)) {
                throw new Exception('chunk news-item.php requires an instance of page as first context argument.');
            }

			// save contextual data to custom variables
			$this->newsItem = $context[0]
		}

        public function populate() {

            $this->newsItem->foo = 'bar'; // add field 'foo' to news item            
        }
    }

`setContext()` will always be executed prior to `populate()`. So within `populate()` you have either access to `$this->context` or your custom variables defined in `setContext()`.

**Attention**: And remember that overwriting `setContext()` in a chunk data provider is optional as is `populate()`. So you may create a chunk data provider just overwriting `setContext()` to do some context validating and storing and omit `populate()`.
