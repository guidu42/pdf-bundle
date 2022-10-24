=======

# Drosalys-pdf-bundle-v1
Drosalys-pdf-bundle-v1 is a bundle for pdf management on symfony projects.
It provides easy way to manage your pdf styles, templates, and storage.

### configuration
```yaml
drosalys_pdf:
  chrome_bin: '%env(CHROME_BIN)%'
  asset_output_path: '%kernel.project_dir%/public/'
  pdfTmpDir: '%kernel.cache_dir%/pdf_tmp/'
#  default_templates_dir: '%kernel.project_dir%/test'
#  default_cache_dir: '%kernel.project_dir%/var/cache/pdf'
```
These are all the conf you need for this bundle.
 - chrome_bin and pdfTmpDir are required. 
 - asset_output_path is required if you need to pass Sass files
 - default_templates_dir and default_cache_dir and optionals. They are used to set up global templates_dir or cache_dir for your pdf.

### Basic Usage
First create an instance of Drosalys\PdfBundle\Models\Pdf;

```php
$pdf = (new Pdf())
            ->setTemplate('pdf/pdf-test.html.twig')
            ->addStyleEntryPoint(new EntryPoint('pdf-style'))
            ->setFileName('test2')
            ->setPdfOptions(['printBackground' => true])
```
This object is the only entity you need to set up for your Pdf.

After you set up all you needed you can call one of the method of Drosalys\PdfBundle\Service\PdfGenerator.
Like renderOutput.

```php
return new Response($pdfGenerator->renderOutput($pdf),
    200, [
    'Content-Type' => 'application/pdf'
]);
```

### 
