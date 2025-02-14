<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        body {
            color: #606c76;
            font-family: 'arabicfont', sans-serif;
            letter-spacing: .01em;
            line-height: 1.6;
        }

        .back-ground {
            /* background-image: url('images/pdf-background.png'); */
            background-image-resize: 6;
            background-repeat: no-repeat;
        }

        .certificate__body {
            padding: 1rem 0;
        }

        .certificate__title {
            font-family: 'DS Arabic', Palatino;
            font-size: 4rem;
        }

        .certificate__recipient-name {
            font-family: 'DS Arabic', cursive;
            font-size: 4rem;
        }

        .certificate__content {
            font-family: 'DS Arabic', cursive;
            background-image-resize: 6;
            background-repeat: no-repeat;
            /* background-image: url('img/ribbon.png'); */
            font-size: 2rem;
            white-space: nowrap;
            font-family: 'arabicfont';
            /* font-family: 'arabicfont', sans-serif; */
        }

        .certificate__description {
            font-family: 'DS Arabic', cursive;
            font-size: 1rem;
            margin: 0 auto;
            max-width: 70%;
        }

        .certificate__date {
            font-size: 1.5rem;
        }

        .certificate__signature {
            font-size: 1.5rem;
        }

        .certificate__footer {
            display: flex;
            justify-content: space-around;
            margin-top: 100px;
        }

        .entry-column {
            display: flex;
            flex-direction: column;
        }

        .entry-column__input {
            font-size: 1.5rem;
            font-family: cursive;
        }

        .entry-column__label {
            border-top: 1px solid;
            font-size: 1rem;
        }

        .certificate__signature .entry-column__input {
            color: #5DADE2;
        }

        .title-decoration__main {
            line-height: 1em;
        }

        .title-decoration__sub {
            font-size: 0.25em;
        }

        .ribbon {
            display: inline-block;
            position: relative;
            height: 1.5em;
            line-height: 1.5em;
            text-align: center;
            padding: 0 2em;
            color: #FFF;
            box-sizing: border-box;
            font-family: 'DS Arabic', cursive;
        }
    </style>
</head>

<body>
    <div class="back-ground">
        <div style="width: 100%; height: 85%; padding:20px; text-align:center; ">
            <div style="width: 100%; height: 85%;  padding:20px; text-align:center; border: 5px">
                <br>
                {{-- <div style="text-align: left !important;">

                </div> --}}
                {{-- <br> --}}
                {{-- <br>
                <br> --}}
                {{-- <img src="{{ asset('images/Certificate_of_Completion.png') }}" width="170" alt=""> --}}
                <br>
                <div class="certificate__header">
                    <div class="certificate__title title-decoration">
                        <span class="title-decoration__main">Certificate</span>
                        <br>
                        <span class="title-decoration__sub">of Completion</span>
                    </div>
                </div>
                <div class="certificate__body">
                    <div class="certificate__description">This is to certify that</div>
                    <div class="certificate__recipient-name">
                        {{ $name }}
                    </div>
                    <div class="certificate__description">has successfully completed</div>
                    <div class="ribbon certificate__content" style="color:#606c76">
                        {{ $program }} program.
                    </div>
                    <div class="certificate__description">

                    </div>
                </div>
                <div class="certificate__footer">
                    <table class="table" style="width: 100%">
                        <tr>
                            <td>
                                <span class="entry-column__input">
                                    {{ $date }}
                                </span>
                            </td>
                            <td style="width: 280px"></td>
                            <td style="width: 340px">
                                <span class="entry-column__input">
                                    Injaz Academy
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 200px">
                                <div class="certificate__date entry-column">

                                    <br>
                                    <br>
                                    <div>
                                        <span class="entry-column__label">completed on </span>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
