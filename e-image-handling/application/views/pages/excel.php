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
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-12" id="importFrm" style="">
                            <h3>Import Data from Single.xl or .xlsx file</h3>
                            <form action="<?php echo base_url('api/read_from_excel'); ?>" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="file" accept=".xl,.xlsx" />
                                </div>
                                <input type="submit" class="btn btn-primary" name="importSubmit" value="Import Data">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>