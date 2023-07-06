<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <section class="py-5">
        <div class="row m-0 g-0 justify-content-center">
            <div class="col-xxl-8 col-xl-9 col-lg-10 col-md-11 col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-01" data-bs-toggle="tab" data-bs-target="#tab-pane-01" type="button" role="tab" aria-controls="tab-pane-01" aria-selected="false">Single</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" id="tab-02" data-bs-toggle="tab" data-bs-target="#tab-pane-02" type="button" role="tab" aria-controls="tab-pane-02" aria-selected="true">Multiple</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-pane-01" role="tabpanel" aria-labelledby="tab-01" tabindex="0">
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12" id="importFrm" style="">
                                    <h3>Import Data from Single or Multiple .csv files into a single Table</h3>
                                    <form action="<?php echo base_url('api/import_from_csv'); ?>" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <input type="file" class="form-control" name="files[]" multiple />
                                        </div>
                                        <input type="submit" class="btn btn-primary" name="importSubmit" value="Import Data">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-02" role="tabpanel" aria-labelledby="tab-02" tabindex="0">
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12" id="importFrm" style="">
                                    <h3>Import Data for Multiple csv Files for Multiple Tables</h3>
                                    <form action="<?php echo base_url('api/import_from_batch_csv'); ?>" method="post" enctype="multipart/form-data">
                                        <div class="row m-0 g-0">
                                            <div class="col-12" id="container">
                                                <template id="template">
                                                    <div class="row m-0">
                                                        <div class="col-md-5">
                                                            <div class="mb-3">
                                                                <input type="file" class="form-control file-upload" name="files[]" onchange="handleFileChange(this)" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="mb-3">
                                                                <input type="text" class="form-control table-name" name="table_names[]" />
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="mb-3">
                                                                <button type="button" class="form-control btn btn-danger" onclick="deleteTemplate(this)">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="row m-0" id="container">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <button type="button" class="form-control btn btn-outline-warning" id="clone-btn">Import Another CSV</button>
                                                </div>
                                            </div>
                                            <script>
                                                var template = document.getElementById('template');
                                                var container = document.getElementById('container');
                                                container.appendChild(template.content.cloneNode(true));
                                                document.getElementById('clone-btn').addEventListener('click', function() {
                                                    container.appendChild(template.content.cloneNode(true));
                                                });

                                                function deleteTemplate(button) {
                                                    var templateElement = button.parentNode.parentNode.parentNode;
                                                    templateElement.parentNode.removeChild(templateElement);
                                                }

                                                function handleFileChange() {
                                                    var array = document.querySelectorAll(".table-name");
                                                    var input = document.querySelectorAll(".file-upload");
                                                    for (let i = 0; i < array.length; i++) {
                                                        array[i].value = input[i].files[0].name.split(".")[0].toLowerCase().replaceAll(" ", "_");
                                                    }
                                                    console.log(input.files[0]);
                                                    // You can perform further processing with the filename here
                                                }
                                            </script>
                                        </div>
                                        <div class="row m-0">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <input type="submit" class="btn btn-primary" name="importSubmit" value="Import Data">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>