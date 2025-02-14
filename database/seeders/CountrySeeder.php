<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                "name" => "Bahrain",
                "phone_code" => "+973",
                "country_code" => "BH",
            ],
            [
                "name" => "Pakistan",
                "phone_code" => "+92",
                "country_code" => "PK",
            ],
            [
                "name" => "Afghanistan",
                "phone_code" => "+93",
                "country_code" => "AF",
            ],
            [
                "name" => "Aland Islands",
                "phone_code" => "+358",
                "country_code" => "AX",
            ],
            [
                "name" => "Albania",
                "phone_code" => "+355",
                "country_code" => "AL",
            ],
            [
                "name" => "Algeria",
                "phone_code" => "+213",
                "country_code" => "DZ",
            ],
            [
                "name" => "AmericanSamoa",
                "phone_code" => "+1684",
                "country_code" => "AS",
            ],
            [
                "name" => "Andorra",
                "phone_code" => "+376",
                "country_code" => "AD",
            ],
            [
                "name" => "Angola",
                "phone_code" => "+244",
                "country_code" => "AO",
            ],
            [
                "name" => "Anguilla",
                "phone_code" => "+1264",
                "country_code" => "AI",
            ],
            [
                "name" => "Antarctica",
                "phone_code" => "+672",
                "country_code" => "AQ",
            ],
            [
                "name" => "Antigua and Barbuda",
                "phone_code" => "+1268",
                "country_code" => "AG",
            ],
            [
                "name" => "Argentina",
                "phone_code" => "+54",
                "country_code" => "AR",
            ],
            [
                "name" => "Armenia",
                "phone_code" => "+374",
                "country_code" => "AM",
            ],
            [
                "name" => "Aruba",
                "phone_code" => "+297",
                "country_code" => "AW",
            ],
            [
                "name" => "Ascension Island",
                "phone_code" => "+247",
                "country_code" => "AC",
            ],
            [
                "name" => "Australia",
                "phone_code" => "+61",
                "country_code" => "AU",
            ],
            [
                "name" => "Austria",
                "phone_code" => "+43",
                "country_code" => "AT",
            ],
            [
                "name" => "Azerbaijan",
                "phone_code" => "+994",
                "country_code" => "AZ",
            ],
            [
                "name" => "Bahamas",
                "phone_code" => "+1242",
                "country_code" => "BS",
            ],
            [
                "name" => "Bangladesh",
                "phone_code" => "+880",
                "country_code" => "BD",
            ],
            [
                "name" => "Barbados",
                "phone_code" => "+1246",
                "country_code" => "BB",
            ],
            [
                "name" => "Belarus",
                "phone_code" => "+375",
                "country_code" => "BY",
            ],
            [
                "name" => "Belgium",
                "phone_code" => "+32",
                "country_code" => "BE",
            ],
            [
                "name" => "Belize",
                "phone_code" => "+501",
                "country_code" => "BZ",
            ],
            [
                "name" => "Benin",
                "phone_code" => "+229",
                "country_code" => "BJ",
            ],
            [
                "name" => "Bermuda",
                "phone_code" => "+1441",
                "country_code" => "BM",
            ],
            [
                "name" => "Bhutan",
                "phone_code" => "+975",
                "country_code" => "BT",
            ],
            [
                "name" => "Bolivia",
                "phone_code" => "+591",
                "country_code" => "BO",
            ],
            [
                "name" => "Bosnia and Herzegovina",
                "phone_code" => "+387",
                "country_code" => "BA",
            ],
            [
                "name" => "Botswana",
                "phone_code" => "+267",
                "country_code" => "BW",
            ],
            [
                "name" => "Brazil",
                "phone_code" => "+55",
                "country_code" => "BR",
            ],
            [
                "name" => "British Indian Ocean Territory",
                "phone_code" => "+246",
                "country_code" => "IO",
            ],
            [
                "name" => "Brunei Darussalam",
                "phone_code" => "+673",
                "country_code" => "BN",
            ],
            [
                "name" => "Bulgaria",
                "phone_code" => "+359",
                "country_code" => "BG",
            ],
            [
                "name" => "Burkina Faso",
                "phone_code" => "+226",
                "country_code" => "BF",
            ],
            [
                "name" => "Burundi",
                "phone_code" => "+257",
                "country_code" => "BI",
            ],
            [
                "name" => "Cambodia",
                "phone_code" => "+855",
                "country_code" => "KH",
            ],
            [
                "name" => "Cameroon",
                "phone_code" => "+237",
                "country_code" => "CM",
            ],
            [
                "name" => "Canada",
                "phone_code" => "+1",
                "country_code" => "CA",
            ],
            [
                "name" => "Cape Verde",
                "phone_code" => "+238",
                "country_code" => "CV",
            ],
            [
                "name" => "Cayman Islands",
                "phone_code" => "+1345",
                "country_code" => "KY",
            ],
            [
                "name" => "Central African Republic",
                "phone_code" => "+236",
                "country_code" => "CF",
            ],
            [
                "name" => "Chad",
                "phone_code" => "+235",
                "country_code" => "TD",
            ],
            [
                "name" => "Chile",
                "phone_code" => "+56",
                "country_code" => "CL",
            ],
            [
                "name" => "China",
                "phone_code" => "+86",
                "country_code" => "CN",
            ],
            [
                "name" => "Christmas Island",
                "phone_code" => "+61",
                "country_code" => "CX",
            ],
            [
                "name" => "Cocos (Keeling) Islands",
                "phone_code" => "+61",
                "country_code" => "CC",
            ],
            [
                "name" => "Colombia",
                "phone_code" => "+57",
                "country_code" => "CO",
            ],
            [
                "name" => "Comoros",
                "phone_code" => "+269",
                "country_code" => "KM",
            ],
            [
                "name" => "Congo",
                "phone_code" => "+242",
                "country_code" => "CG",
            ],
            [
                "name" => "Cook Islands",
                "phone_code" => "+682",
                "country_code" => "CK",
            ],
            [
                "name" => "Costa Rica",
                "phone_code" => "+506",
                "country_code" => "CR",
            ],
            [
                "name" => "Croatia",
                "phone_code" => "+385",
                "country_code" => "HR",
            ],
            [
                "name" => "Cuba",
                "phone_code" => "+53",
                "country_code" => "CU",
            ],
            [
                "name" => "Cyprus",
                "phone_code" => "+357",
                "country_code" => "CY",
            ],
            [
                "name" => "Czech Republic",
                "phone_code" => "+420",
                "country_code" => "CZ",
            ],
            [
                "name" => "Democratic Republic of the Congo",
                "phone_code" => "+243",
                "country_code" => "CD",
            ],
            [
                "name" => "Denmark",
                "phone_code" => "+45",
                "country_code" => "DK",
            ],
            [
                "name" => "Djibouti",
                "phone_code" => "+253",
                "country_code" => "DJ",
            ],
            [
                "name" => "Dominica",
                "phone_code" => "+1767",
                "country_code" => "DM",
            ],
            [
                "name" => "Dominican Republic",
                "phone_code" => "+1849",
                "country_code" => "DO",
            ],
            [
                "name" => "Ecuador",
                "phone_code" => "+593",
                "country_code" => "EC",
            ],
            [
                "name" => "Egypt",
                "phone_code" => "+20",
                "country_code" => "EG",
            ],
            [
                "name" => "El Salvador",
                "phone_code" => "+503",
                "country_code" => "SV",
            ],
            [
                "name" => "Equatorial Guinea",
                "phone_code" => "+240",
                "country_code" => "GQ",
            ],
            [
                "name" => "Eritrea",
                "phone_code" => "+291",
                "country_code" => "ER",
            ],
            [
                "name" => "Estonia",
                "phone_code" => "+372",
                "country_code" => "EE",
            ],
            [
                "name" => "Eswatini",
                "phone_code" => "+268",
                "country_code" => "SZ",
            ],
            [
                "name" => "Ethiopia",
                "phone_code" => "+251",
                "country_code" => "ET",
            ],
            [
                "name" => "Falkland Islands (Malvinas)",
                "phone_code" => "+500",
                "country_code" => "FK",
            ],
            [
                "name" => "Faroe Islands",
                "phone_code" => "+298",
                "country_code" => "FO",
            ],
            [
                "name" => "Fiji",
                "phone_code" => "+679",
                "country_code" => "FJ",
            ],
            [
                "name" => "Finland",
                "phone_code" => "+358",
                "country_code" => "FI",
            ],
            [
                "name" => "France",
                "phone_code" => "+33",
                "country_code" => "FR",
            ],
            [
                "name" => "French Guiana",
                "phone_code" => "+594",
                "country_code" => "GF",
            ],
            [
                "name" => "French Polynesia",
                "phone_code" => "+689",
                "country_code" => "PF",
            ],
            [
                "name" => "Gabon",
                "phone_code" => "+241",
                "country_code" => "GA",
            ],
            [
                "name" => "Gambia",
                "phone_code" => "+220",
                "country_code" => "GM",
            ],
            [
                "name" => "Georgia",
                "phone_code" => "+995",
                "country_code" => "GE",
            ],
            [
                "name" => "Germany",
                "phone_code" => "+49",
                "country_code" => "DE",
            ],
            [
                "name" => "Ghana",
                "phone_code" => "+233",
                "country_code" => "GH",
            ],
            [
                "name" => "Gibraltar",
                "phone_code" => "+350",
                "country_code" => "GI",
            ],
            [
                "name" => "Greece",
                "phone_code" => "+30",
                "country_code" => "GR",
            ],
            [
                "name" => "Greenland",
                "phone_code" => "+299",
                "country_code" => "GL",
            ],
            [
                "name" => "Grenada",
                "phone_code" => "+1473",
                "country_code" => "GD",
            ],
            [
                "name" => "Guadeloupe",
                "phone_code" => "+590",
                "country_code" => "GP",
            ],
            [
                "name" => "Guam",
                "phone_code" => "+1671",
                "country_code" => "GU",
            ],
            [
                "name" => "Guatemala",
                "phone_code" => "+502",
                "country_code" => "GT",
            ],
            [
                "name" => "Guernsey",
                "phone_code" => "+44",
                "country_code" => "GG",
            ],
            [
                "name" => "Guinea",
                "phone_code" => "+224",
                "country_code" => "GN",
            ],
            [
                "name" => "Guinea-Bissau",
                "phone_code" => "+245",
                "country_code" => "GW",
            ],
            [
                "name" => "Guyana",
                "phone_code" => "+592",
                "country_code" => "GY",
            ],
            [
                "name" => "Haiti",
                "phone_code" => "+509",
                "country_code" => "HT",
            ],
            [
                "name" => "Holy See (Vatican City State)",
                "phone_code" => "+379",
                "country_code" => "VA",
            ],
            [
                "name" => "Honduras",
                "phone_code" => "+504",
                "country_code" => "HN",
            ],
            [
                "name" => "Hong Kong",
                "phone_code" => "+852",
                "country_code" => "HK",
            ],
            [
                "name" => "Hungary",
                "phone_code" => "+36",
                "country_code" => "HU",
            ],
            [
                "name" => "Iceland",
                "phone_code" => "+354",
                "country_code" => "IS",
            ],
            [
                "name" => "India",
                "phone_code" => "+91",
                "country_code" => "IN",
            ],
            [
                "name" => "Indonesia",
                "phone_code" => "+62",
                "country_code" => "ID",
            ],
            [
                "name" => "Iran",
                "phone_code" => "+98",
                "country_code" => "IR",
            ],
            [
                "name" => "Iraq",
                "phone_code" => "+964",
                "country_code" => "IQ",
            ],
            [
                "name" => "Ireland",
                "phone_code" => "+353",
                "country_code" => "IE",
            ],
            [
                "name" => "Isle of Man",
                "phone_code" => "+44",
                "country_code" => "IM",
            ],
            [
                "name" => "Israel",
                "phone_code" => "+972",
                "country_code" => "IL",
            ],
            [
                "name" => "Italy",
                "phone_code" => "+39",
                "country_code" => "IT",
            ],
            [
                "name" => "Ivory Coast / Cote d'Ivoire",
                "phone_code" => "+225",
                "country_code" => "CI",
            ],
            [
                "name" => "Jamaica",
                "phone_code" => "+1876",
                "country_code" => "JM",
            ],
            [
                "name" => "Japan",
                "phone_code" => "+81",
                "country_code" => "JP",
            ],
            [
                "name" => "Jersey",
                "phone_code" => "+44",
                "country_code" => "JE",
            ],
            [
                "name" => "Jordan",
                "phone_code" => "+962",
                "country_code" => "JO",
            ],
            [
                "name" => "Kazakhstan",
                "phone_code" => "+77",
                "country_code" => "KZ",
            ],
            [
                "name" => "Kenya",
                "phone_code" => "+254",
                "country_code" => "KE",
            ],
            [
                "name" => "Kiribati",
                "phone_code" => "+686",
                "country_code" => "KI",
            ],
            [
                "name" => "Korea, Democratic People's Republic of Korea",
                "phone_code" => "+850",
                "country_code" => "KP",
            ],
            [
                "name" => "Korea, Republic of South Korea",
                "phone_code" => "+82",
                "country_code" => "KR",
            ],
            [
                "name" => "Kosovo",
                "phone_code" => "+383",
                "country_code" => "XK",
            ],
            [
                "name" => "Kuwait",
                "phone_code" => "+965",
                "country_code" => "KW",
            ],
            [
                "name" => "Kyrgyzstan",
                "phone_code" => "+996",
                "country_code" => "KG",
            ],
            [
                "name" => "Laos",
                "phone_code" => "+856",
                "country_code" => "LA",
            ],
            [
                "name" => "Latvia",
                "phone_code" => "+371",
                "country_code" => "LV",
            ],
            [
                "name" => "Lebanon",
                "phone_code" => "+961",
                "country_code" => "LB",
            ],
            [
                "name" => "Lesotho",
                "phone_code" => "+266",
                "country_code" => "LS",
            ],
            [
                "name" => "Liberia",
                "phone_code" => "+231",
                "country_code" => "LR",
            ],
            [
                "name" => "Libya",
                "phone_code" => "+218",
                "country_code" => "LY",
            ],
            [
                "name" => "Liechtenstein",
                "phone_code" => "+423",
                "country_code" => "LI",
            ],
            [
                "name" => "Lithuania",
                "phone_code" => "+370",
                "country_code" => "LT",
            ],
            [
                "name" => "Luxembourg",
                "phone_code" => "+352",
                "country_code" => "LU",
            ],
            [
                "name" => "Macau",
                "phone_code" => "+853",
                "country_code" => "MO",
            ],
            [
                "name" => "Madagascar",
                "phone_code" => "+261",
                "country_code" => "MG",
            ],
            [
                "name" => "Malawi",
                "phone_code" => "+265",
                "country_code" => "MW",
            ],
            [
                "name" => "Malaysia",
                "phone_code" => "+60",
                "country_code" => "MY",
            ],
            [
                "name" => "Maldives",
                "phone_code" => "+960",
                "country_code" => "MV",
            ],
            [
                "name" => "Mali",
                "phone_code" => "+223",
                "country_code" => "ML",
            ],
            [
                "name" => "Malta",
                "phone_code" => "+356",
                "country_code" => "MT",
            ],
            [
                "name" => "Marshall Islands",
                "phone_code" => "+692",
                "country_code" => "MH",
            ],
            [
                "name" => "Martinique",
                "phone_code" => "+596",
                "country_code" => "MQ",
            ],
            [
                "name" => "Mauritania",
                "phone_code" => "+222",
                "country_code" => "MR",
            ],
            [
                "name" => "Mauritius",
                "phone_code" => "+230",
                "country_code" => "MU",
            ],
            [
                "name" => "Mayotte",
                "phone_code" => "+262",
                "country_code" => "YT",
            ],
            [
                "name" => "Mexico",
                "phone_code" => "+52",
                "country_code" => "MX",
            ],
            [
                "name" => "Micronesia, Federated States of Micronesia",
                "phone_code" => "+691",
                "country_code" => "FM",
            ],
            [
                "name" => "Moldova",
                "phone_code" => "+373",
                "country_code" => "MD",
            ],
            [
                "name" => "Monaco",
                "phone_code" => "+377",
                "country_code" => "MC",
            ],
            [
                "name" => "Mongolia",
                "phone_code" => "+976",
                "country_code" => "MN",
            ],
            [
                "name" => "Montenegro",
                "phone_code" => "+382",
                "country_code" => "ME",
            ],
            [
                "name" => "Montserrat",
                "phone_code" => "+1664",
                "country_code" => "MS",
            ],
            [
                "name" => "Morocco",
                "phone_code" => "+212",
                "country_code" => "MA",
            ],
            [
                "name" => "Mozambique",
                "phone_code" => "+258",
                "country_code" => "MZ",
            ],
            [
                "name" => "Myanmar",
                "phone_code" => "+95",
                "country_code" => "MM",
            ],
            [
                "name" => "Namibia",
                "phone_code" => "+264",
                "country_code" => "NA",
            ],
            [
                "name" => "Nauru",
                "phone_code" => "+674",
                "country_code" => "NR",
            ],
            [
                "name" => "Nepal",
                "phone_code" => "+977",
                "country_code" => "NP",
            ],
            [
                "name" => "Netherlands",
                "phone_code" => "+31",
                "country_code" => "NL",
            ],
            [
                "name" => "Netherlands Antilles",
                "phone_code" => "+599",
                "country_code" => "AN",
            ],
            [
                "name" => "New Caledonia",
                "phone_code" => "+687",
                "country_code" => "NC",
            ],
            [
                "name" => "New Zealand",
                "phone_code" => "+64",
                "country_code" => "NZ",
            ],
            [
                "name" => "Nicaragua",
                "phone_code" => "+505",
                "country_code" => "NI",
            ],
            [
                "name" => "Niger",
                "phone_code" => "+227",
                "country_code" => "NE",
            ],
            [
                "name" => "Nigeria",
                "phone_code" => "+234",
                "country_code" => "NG",
            ],
            [
                "name" => "Niue",
                "phone_code" => "+683",
                "country_code" => "NU",
            ],
            [
                "name" => "Norfolk Island",
                "phone_code" => "+672",
                "country_code" => "NF",
            ],
            [
                "name" => "North Macedonia",
                "phone_code" => "+389",
                "country_code" => "MK",
            ],
            [
                "name" => "Northern Mariana Islands",
                "phone_code" => "+1670",
                "country_code" => "MP",
            ],
            [
                "name" => "Norway",
                "phone_code" => "+47",
                "country_code" => "NO",
            ],
            [
                "name" => "Oman",
                "phone_code" => "+968",
                "country_code" => "OM",
            ],
            [
                "name" => "Palau",
                "phone_code" => "+680",
                "country_code" => "PW",
            ],
            [
                "name" => "Palestine",
                "phone_code" => "+970",
                "country_code" => "PS",
            ],
            [
                "name" => "Panama",
                "phone_code" => "+507",
                "country_code" => "PA",
            ],
            [
                "name" => "Papua New Guinea",
                "phone_code" => "+675",
                "country_code" => "PG",
            ],
            [
                "name" => "Paraguay",
                "phone_code" => "+595",
                "country_code" => "PY",
            ],
            [
                "name" => "Peru",
                "phone_code" => "+51",
                "country_code" => "PE",
            ],
            [
                "name" => "Philippines",
                "phone_code" => "+63",
                "country_code" => "PH",
            ],
            [
                "name" => "Pitcairn",
                "phone_code" => "+872",
                "country_code" => "PN",
            ],
            [
                "name" => "Poland",
                "phone_code" => "+48",
                "country_code" => "PL",
            ],
            [
                "name" => "Portugal",
                "phone_code" => "+351",
                "country_code" => "PT",
            ],
            [
                "name" => "Puerto Rico",
                "phone_code" => "+1939",
                "country_code" => "PR",
            ],
            [
                "name" => "Qatar",
                "phone_code" => "+974",
                "country_code" => "QA",
            ],
            [
                "name" => "Reunion",
                "phone_code" => "+262",
                "country_code" => "RE",
            ],
            [
                "name" => "Romania",
                "phone_code" => "+40",
                "country_code" => "RO",
            ],
            [
                "name" => "Russia",
                "phone_code" => "+7",
                "country_code" => "RU",
            ],
            [
                "name" => "Rwanda",
                "phone_code" => "+250",
                "country_code" => "RW",
            ],
            [
                "name" => "Saint Barthelemy",
                "phone_code" => "+590",
                "country_code" => "BL",
            ],
            [
                "name" => "Saint Helena, Ascension and Tristan Da Cunha",
                "phone_code" => "+290",
                "country_code" => "SH",
            ],
            [
                "name" => "Saint Kitts and Nevis",
                "phone_code" => "+1869",
                "country_code" => "KN",
            ],
            [
                "name" => "Saint Lucia",
                "phone_code" => "+1758",
                "country_code" => "LC",
            ],
            [
                "name" => "Saint Martin",
                "phone_code" => "+590",
                "country_code" => "MF",
            ],
            [
                "name" => "Saint Pierre and Miquelon",
                "phone_code" => "+508",
                "country_code" => "PM",
            ],
            [
                "name" => "Saint Vincent and the Grenadines",
                "phone_code" => "+1784",
                "country_code" => "VC",
            ],
            [
                "name" => "Samoa",
                "phone_code" => "+685",
                "country_code" => "WS",
            ],
            [
                "name" => "San Marino",
                "phone_code" => "+378",
                "country_code" => "SM",
            ],
            [
                "name" => "Sao Tome and Principe",
                "phone_code" => "+239",
                "country_code" => "ST",
            ],
            [
                "name" => "Saudi Arabia",
                "phone_code" => "+966",
                "country_code" => "SA",
            ],
            [
                "name" => "Senegal",
                "phone_code" => "+221",
                "country_code" => "SN",
            ],
            [
                "name" => "Serbia",
                "phone_code" => "+381",
                "country_code" => "RS",
            ],
            [
                "name" => "Seychelles",
                "phone_code" => "+248",
                "country_code" => "SC",
            ],
            [
                "name" => "Sierra Leone",
                "phone_code" => "+232",
                "country_code" => "SL",
            ],
            [
                "name" => "Singapore",
                "phone_code" => "+65",
                "country_code" => "SG",
            ],
            [
                "name" => "Sint Maarten",
                "phone_code" => "+1721",
                "country_code" => "SX",
            ],
            [
                "name" => "Slovakia",
                "phone_code" => "+421",
                "country_code" => "SK",
            ],
            [
                "name" => "Slovenia",
                "phone_code" => "+386",
                "country_code" => "SI",
            ],
            [
                "name" => "Solomon Islands",
                "phone_code" => "+677",
                "country_code" => "SB",
            ],
            [
                "name" => "Somalia",
                "phone_code" => "+252",
                "country_code" => "SO",
            ],
            [
                "name" => "South Africa",
                "phone_code" => "+27",
                "country_code" => "ZA",
            ],
            [
                "name" => "South Georgia and the South Sandwich Islands",
                "phone_code" => "+500",
                "country_code" => "GS",
            ],
            [
                "name" => "South Sudan",
                "phone_code" => "+211",
                "country_code" => "SS",
            ],
            [
                "name" => "Spain",
                "phone_code" => "+34",
                "country_code" => "ES",
            ],
            [
                "name" => "Sri Lanka",
                "phone_code" => "+94",
                "country_code" => "LK",
            ],
            [
                "name" => "Sudan",
                "phone_code" => "+249",
                "country_code" => "SD",
            ],
            [
                "name" => "Suriname",
                "phone_code" => "+597",
                "country_code" => "SR",
            ],
            [
                "name" => "Svalbard and Jan Mayen",
                "phone_code" => "+47",
                "country_code" => "SJ",
            ],
            [
                "name" => "Sweden",
                "phone_code" => "+46",
                "country_code" => "SE",
            ],
            [
                "name" => "Switzerland",
                "phone_code" => "+41",
                "country_code" => "CH",
            ],
            [
                "name" => "Syrian Arab Republic",
                "phone_code" => "+963",
                "country_code" => "SY",
            ],
            [
                "name" => "Taiwan",
                "phone_code" => "+886",
                "country_code" => "TW",
            ],
            [
                "name" => "Tajikistan",
                "phone_code" => "+992",
                "country_code" => "TJ",
            ],
            [
                "name" => "Tanzania, United Republic of Tanzania",
                "phone_code" => "+255",
                "country_code" => "TZ",
            ],
            [
                "name" => "Thailand",
                "phone_code" => "+66",
                "country_code" => "TH",
            ],
            [
                "name" => "Timor-Leste",
                "phone_code" => "+670",
                "country_code" => "TL",
            ],
            [
                "name" => "Togo",
                "phone_code" => "+228",
                "country_code" => "TG",
            ],
            [
                "name" => "Tokelau",
                "phone_code" => "+690",
                "country_code" => "TK",
            ],
            [
                "name" => "Tonga",
                "phone_code" => "+676",
                "country_code" => "TO",
            ],
            [
                "name" => "Trinidad and Tobago",
                "phone_code" => "+1868",
                "country_code" => "TT",
            ],
            [
                "name" => "Tunisia",
                "phone_code" => "+216",
                "country_code" => "TN",
            ],
            [
                "name" => "Turkey",
                "phone_code" => "+90",
                "country_code" => "TR",
            ],
            [
                "name" => "Turkmenistan",
                "phone_code" => "+993",
                "country_code" => "TM",
            ],
            [
                "name" => "Turks and Caicos Islands",
                "phone_code" => "+1649",
                "country_code" => "TC",
            ],
            [
                "name" => "Tuvalu",
                "phone_code" => "+688",
                "country_code" => "TV",
            ],
            [
                "name" => "Uganda",
                "phone_code" => "+256",
                "country_code" => "UG",
            ],
            [
                "name" => "Ukraine",
                "phone_code" => "+380",
                "country_code" => "UA",
            ],
            [
                "name" => "United Arab Emirates",
                "phone_code" => "+971",
                "country_code" => "AE",
            ],
            [
                "name" => "United Kingdom",
                "phone_code" => "+44",
                "country_code" => "GB",
            ],
            [
                "name" => "United States",
                "phone_code" => "+1",
                "country_code" => "US",
            ],
            [
                "name" => "Uruguay",
                "phone_code" => "+598",
                "country_code" => "UY",
            ],
            [
                "name" => "Uzbekistan",
                "phone_code" => "+998",
                "country_code" => "UZ",
            ],
            [
                "name" => "Vanuatu",
                "phone_code" => "+678",
                "country_code" => "VU",
            ],
            [
                "name" => "Venezuela, Bolivarian Republic of Venezuela",
                "phone_code" => "+58",
                "country_code" => "VE",
            ],
            [
                "name" => "Vietnam",
                "phone_code" => "+84",
                "country_code" => "VN",
            ],
            [
                "name" => "Virgin Islands, British",
                "phone_code" => "+1284",
                "country_code" => "VG",
            ],
            [
                "name" => "Virgin Islands, U.S.",
                "phone_code" => "+1340",
                "country_code" => "VI",
            ],
            [
                "name" => "Wallis and Futuna",
                "phone_code" => "+681",
                "country_code" => "WF",
            ],
            [
                "name" => "Yemen",
                "phone_code" => "+967",
                "country_code" => "YE",
            ],
            [
                "name" => "Zambia",
                "phone_code" => "+260",
                "country_code" => "ZM",
            ],
            [
                "name" => "Zimbabwe",
                "phone_code" => "+263",
                "country_code" => "ZW",
            ],
        ];

        DB::table('countries')->insert($countries);
    
    }
}
