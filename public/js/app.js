function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function (_e) { function e(_x) { return _e.apply(this, arguments); } e.toString = function () { return _e.toString(); }; return e; }(function (e) { throw e; }), f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function (_e2) { function e(_x2) { return _e2.apply(this, arguments); } e.toString = function () { return _e2.toString(); }; return e; }(function (e) { didErr = true; err = e; }), f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
/*!
 * jQuery Validation Plugin v1.19.2
 *
 * https://jqueryvalidation.org/
 *
 * Copyright (c) 2020 Jörn Zaefferer
 * Released under the MIT license
 */

(function (factory) {
  if (typeof define === "function" && define.amd) {
    define(["jquery", "./jquery.validate"], factory);
  } else if ((typeof module === "undefined" ? "undefined" : _typeof(module)) === "object" && module.exports) {
    module.exports = factory(require("jquery"));
  } else {
    factory(jQuery);
  }
})(function ($) {
  (function () {
    function stripHtml(value) {
      // Remove html tags and space chars
      return value.replace(/<.[^<>]*?>/g, " ").replace(/&nbsp;|&#160;/gi, " ") // Remove punctuation
      .replace(/[.(),;:!?%#$'\"_+=\/\-“”’]*/g, "");
    }

    $.validator.addMethod("maxWords", function (value, element, params) {
      return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length <= params;
    }, $.validator.format("Please enter {0} words or less."));
    $.validator.addMethod("minWords", function (value, element, params) {
      return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params;
    }, $.validator.format("Please enter at least {0} words."));
    $.validator.addMethod("rangeWords", function (value, element, params) {
      var valueStripped = stripHtml(value),
          regex = /\b\w+\b/g;
      return this.optional(element) || valueStripped.match(regex).length >= params[0] && valueStripped.match(regex).length <= params[1];
    }, $.validator.format("Please enter between {0} and {1} words."));
  })();
  /**
   * This is used in the United States to process payments, deposits,
   * or transfers using the Automated Clearing House (ACH) or Fedwire
   * systems. A very common use case would be to validate a form for
   * an ACH bill payment.
   */


  $.validator.addMethod("abaRoutingNumber", function (value) {
    var checksum = 0;
    var tokens = value.split("");
    var length = tokens.length; // Length Check

    if (length !== 9) {
      return false;
    } // Calc the checksum
    // https://en.wikipedia.org/wiki/ABA_routing_transit_number


    for (var i = 0; i < length; i += 3) {
      checksum += parseInt(tokens[i], 10) * 3 + parseInt(tokens[i + 1], 10) * 7 + parseInt(tokens[i + 2], 10);
    } // If not zero and divisible by 10 then valid


    if (checksum !== 0 && checksum % 10 === 0) {
      return true;
    }

    return false;
  }, "Please enter a valid routing number."); // Accept a value from a file input based on a required mimetype

  $.validator.addMethod("accept", function (value, element, param) {
    // Split mime on commas in case we have multiple types we can accept
    var typeParam = typeof param === "string" ? param.replace(/\s/g, "") : "image/*",
        optionalValue = this.optional(element),
        i,
        file,
        regex; // Element is optional

    if (optionalValue) {
      return optionalValue;
    }

    if ($(element).attr("type") === "file") {
      // Escape string to be used in the regex
      // see: https://stackoverflow.com/questions/3446170/escape-string-for-use-in-javascript-regex
      // Escape also "/*" as "/.*" as a wildcard
      typeParam = typeParam.replace(/[\-\[\]\/\{\}\(\)\+\?\.\\\^\$\|]/g, "\\$&").replace(/,/g, "|").replace(/\/\*/g, "/.*"); // Check if the element has a FileList before checking each file

      if (element.files && element.files.length) {
        regex = new RegExp(".?(" + typeParam + ")$", "i");

        for (i = 0; i < element.files.length; i++) {
          file = element.files[i]; // Grab the mimetype from the loaded file, verify it matches

          if (!file.type.match(regex)) {
            return false;
          }
        }
      }
    } // Either return true because we've validated each file, or because the
    // browser does not support element.files and the FileList feature


    return true;
  }, $.validator.format("Please enter a value with a valid mimetype."));
  $.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^\w+$/i.test(value);
  }, "Letters, numbers, and underscores only please");
  /*
   * Dutch bank account numbers (not 'giro' numbers) have 9 digits
   * and pass the '11 check'.
   * We accept the notation with spaces, as that is common.
   * acceptable: 123456789 or 12 34 56 789
   */

  $.validator.addMethod("bankaccountNL", function (value, element) {
    if (this.optional(element)) {
      return true;
    }

    if (!/^[0-9]{9}|([0-9]{2} ){3}[0-9]{3}$/.test(value)) {
      return false;
    } // Now '11 check'


    var account = value.replace(/ /g, ""),
        // Remove spaces
    sum = 0,
        len = account.length,
        pos,
        factor,
        digit;

    for (pos = 0; pos < len; pos++) {
      factor = len - pos;
      digit = account.substring(pos, pos + 1);
      sum = sum + factor * digit;
    }

    return sum % 11 === 0;
  }, "Please specify a valid bank account number");
  $.validator.addMethod("bankorgiroaccountNL", function (value, element) {
    return this.optional(element) || $.validator.methods.bankaccountNL.call(this, value, element) || $.validator.methods.giroaccountNL.call(this, value, element);
  }, "Please specify a valid bank or giro account number");
  /**
   * BIC is the business identifier code (ISO 9362). This BIC check is not a guarantee for authenticity.
   *
   * BIC pattern: BBBBCCLLbbb (8 or 11 characters long; bbb is optional)
   *
   * Validation is case-insensitive. Please make sure to normalize input yourself.
   *
   * BIC definition in detail:
   * - First 4 characters - bank code (only letters)
   * - Next 2 characters - ISO 3166-1 alpha-2 country code (only letters)
   * - Next 2 characters - location code (letters and digits)
   *   a. shall not start with '0' or '1'
   *   b. second character must be a letter ('O' is not allowed) or digit ('0' for test (therefore not allowed), '1' denoting passive participant, '2' typically reverse-billing)
   * - Last 3 characters - branch code, optional (shall not start with 'X' except in case of 'XXX' for primary office) (letters and digits)
   */

  $.validator.addMethod("bic", function (value, element) {
    return this.optional(element) || /^([A-Z]{6}[A-Z2-9][A-NP-Z1-9])(X{3}|[A-WY-Z0-9][A-Z0-9]{2})?$/.test(value.toUpperCase());
  }, "Please specify a valid BIC code");
  /*
   * Código de identificación fiscal ( CIF ) is the tax identification code for Spanish legal entities
   * Further rules can be found in Spanish on http://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
   *
   * Spanish CIF structure:
   *
   * [ T ][ P ][ P ][ N ][ N ][ N ][ N ][ N ][ C ]
   *
   * Where:
   *
   * T: 1 character. Kind of Organization Letter: [ABCDEFGHJKLMNPQRSUVW]
   * P: 2 characters. Province.
   * N: 5 characters. Secuencial Number within the province.
   * C: 1 character. Control Digit: [0-9A-J].
   *
   * [ T ]: Kind of Organizations. Possible values:
   *
   *   A. Corporations
   *   B. LLCs
   *   C. General partnerships
   *   D. Companies limited partnerships
   *   E. Communities of goods
   *   F. Cooperative Societies
   *   G. Associations
   *   H. Communities of homeowners in horizontal property regime
   *   J. Civil Societies
   *   K. Old format
   *   L. Old format
   *   M. Old format
   *   N. Nonresident entities
   *   P. Local authorities
   *   Q. Autonomous bodies, state or not, and the like, and congregations and religious institutions
   *   R. Congregations and religious institutions (since 2008 ORDER EHA/451/2008)
   *   S. Organs of State Administration and regions
   *   V. Agrarian Transformation
   *   W. Permanent establishments of non-resident in Spain
   *
   * [ C ]: Control Digit. It can be a number or a letter depending on T value:
   * [ T ]  -->  [ C ]
   * ------    ----------
   *   A         Number
   *   B         Number
   *   E         Number
   *   H         Number
   *   K         Letter
   *   P         Letter
   *   Q         Letter
   *   S         Letter
   *
   */

  $.validator.addMethod("cifES", function (value, element) {
    "use strict";

    if (this.optional(element)) {
      return true;
    }

    var cifRegEx = new RegExp(/^([ABCDEFGHJKLMNPQRSUVW])(\d{7})([0-9A-J])$/gi);
    var letter = value.substring(0, 1),
        // [ T ]
    number = value.substring(1, 8),
        // [ P ][ P ][ N ][ N ][ N ][ N ][ N ]
    control = value.substring(8, 9),
        // [ C ]
    all_sum = 0,
        even_sum = 0,
        odd_sum = 0,
        i,
        n,
        control_digit,
        control_letter;

    function isOdd(n) {
      return n % 2 === 0;
    } // Quick format test


    if (value.length !== 9 || !cifRegEx.test(value)) {
      return false;
    }

    for (i = 0; i < number.length; i++) {
      n = parseInt(number[i], 10); // Odd positions

      if (isOdd(i)) {
        // Odd positions are multiplied first.
        n *= 2; // If the multiplication is bigger than 10 we need to adjust

        odd_sum += n < 10 ? n : n - 9; // Even positions
        // Just sum them
      } else {
        even_sum += n;
      }
    }

    all_sum = even_sum + odd_sum;
    control_digit = (10 - all_sum.toString().substr(-1)).toString();
    control_digit = parseInt(control_digit, 10) > 9 ? "0" : control_digit;
    control_letter = "JABCDEFGHI".substr(control_digit, 1).toString(); // Control must be a digit

    if (letter.match(/[ABEH]/)) {
      return control === control_digit; // Control must be a letter
    } else if (letter.match(/[KPQS]/)) {
      return control === control_letter;
    } // Can be either


    return control === control_digit || control === control_letter;
  }, "Please specify a valid CIF number.");
  /*
   * Brazillian CNH number (Carteira Nacional de Habilitacao) is the License Driver number.
   * CNH numbers have 11 digits in total: 9 numbers followed by 2 check numbers that are being used for validation.
   */

  $.validator.addMethod("cnhBR", function (value) {
    // Removing special characters from value
    value = value.replace(/([~!@#$%^&*()_+=`{}\[\]\-|\\:;'<>,.\/? ])+/g, ""); // Checking value to have 11 digits only

    if (value.length !== 11) {
      return false;
    }

    var sum = 0,
        dsc = 0,
        firstChar,
        firstCN,
        secondCN,
        i,
        j,
        v;
    firstChar = value.charAt(0);

    if (new Array(12).join(firstChar) === value) {
      return false;
    } // Step 1 - using first Check Number:


    for (i = 0, j = 9, v = 0; i < 9; ++i, --j) {
      sum += +(value.charAt(i) * j);
    }

    firstCN = sum % 11;

    if (firstCN >= 10) {
      firstCN = 0;
      dsc = 2;
    }

    sum = 0;

    for (i = 0, j = 1, v = 0; i < 9; ++i, ++j) {
      sum += +(value.charAt(i) * j);
    }

    secondCN = sum % 11;

    if (secondCN >= 10) {
      secondCN = 0;
    } else {
      secondCN = secondCN - dsc;
    }

    return String(firstCN).concat(secondCN) === value.substr(-2);
  }, "Please specify a valid CNH number");
  /*
   * Brazillian value number (Cadastrado de Pessoas Juridica).
   * value numbers have 14 digits in total: 12 numbers followed by 2 check numbers that are being used for validation.
   */

  $.validator.addMethod("cnpjBR", function (value, element) {
    "use strict";

    if (this.optional(element)) {
      return true;
    } // Removing no number


    value = value.replace(/[^\d]+/g, ""); // Checking value to have 14 digits only

    if (value.length !== 14) {
      return false;
    } // Elimina values invalidos conhecidos


    if (value === "00000000000000" || value === "11111111111111" || value === "22222222222222" || value === "33333333333333" || value === "44444444444444" || value === "55555555555555" || value === "66666666666666" || value === "77777777777777" || value === "88888888888888" || value === "99999999999999") {
      return false;
    } // Valida DVs


    var tamanho = value.length - 2;
    var numeros = value.substring(0, tamanho);
    var digitos = value.substring(tamanho);
    var soma = 0;
    var pos = tamanho - 7;

    for (var i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;

      if (pos < 2) {
        pos = 9;
      }
    }

    var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

    if (resultado !== parseInt(digitos.charAt(0), 10)) {
      return false;
    }

    tamanho = tamanho + 1;
    numeros = value.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;

    for (var il = tamanho; il >= 1; il--) {
      soma += numeros.charAt(tamanho - il) * pos--;

      if (pos < 2) {
        pos = 9;
      }
    }

    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

    if (resultado !== parseInt(digitos.charAt(1), 10)) {
      return false;
    }

    return true;
  }, "Please specify a CNPJ value number");
  /*
   * Brazillian CPF number (Cadastrado de Pessoas Físicas) is the equivalent of a Brazilian tax registration number.
   * CPF numbers have 11 digits in total: 9 numbers followed by 2 check numbers that are being used for validation.
   */

  $.validator.addMethod("cpfBR", function (value, element) {
    "use strict";

    if (this.optional(element)) {
      return true;
    } // Removing special characters from value


    value = value.replace(/([~!@#$%^&*()_+=`{}\[\]\-|\\:;'<>,.\/? ])+/g, ""); // Checking value to have 11 digits only

    if (value.length !== 11) {
      return false;
    }

    var sum = 0,
        firstCN,
        secondCN,
        checkResult,
        i;
    firstCN = parseInt(value.substring(9, 10), 10);
    secondCN = parseInt(value.substring(10, 11), 10);

    checkResult = function checkResult(sum, cn) {
      var result = sum * 10 % 11;

      if (result === 10 || result === 11) {
        result = 0;
      }

      return result === cn;
    }; // Checking for dump data


    if (value === "" || value === "00000000000" || value === "11111111111" || value === "22222222222" || value === "33333333333" || value === "44444444444" || value === "55555555555" || value === "66666666666" || value === "77777777777" || value === "88888888888" || value === "99999999999") {
      return false;
    } // Step 1 - using first Check Number:


    for (i = 1; i <= 9; i++) {
      sum = sum + parseInt(value.substring(i - 1, i), 10) * (11 - i);
    } // If first Check Number (CN) is valid, move to Step 2 - using second Check Number:


    if (checkResult(sum, firstCN)) {
      sum = 0;

      for (i = 1; i <= 10; i++) {
        sum = sum + parseInt(value.substring(i - 1, i), 10) * (12 - i);
      }

      return checkResult(sum, secondCN);
    }

    return false;
  }, "Please specify a valid CPF number"); // https://jqueryvalidation.org/creditcard-method/
  // based on https://en.wikipedia.org/wiki/Luhn_algorithm

  $.validator.addMethod("creditcard", function (value, element) {
    if (this.optional(element)) {
      return "dependency-mismatch";
    } // Accept only spaces, digits and dashes


    if (/[^0-9 \-]+/.test(value)) {
      return false;
    }

    var nCheck = 0,
        nDigit = 0,
        bEven = false,
        n,
        cDigit;
    value = value.replace(/\D/g, ""); // Basing min and max length on
    // https://dev.ean.com/general-info/valid-card-types/

    if (value.length < 13 || value.length > 19) {
      return false;
    }

    for (n = value.length - 1; n >= 0; n--) {
      cDigit = value.charAt(n);
      nDigit = parseInt(cDigit, 10);

      if (bEven) {
        if ((nDigit *= 2) > 9) {
          nDigit -= 9;
        }
      }

      nCheck += nDigit;
      bEven = !bEven;
    }

    return nCheck % 10 === 0;
  }, "Please enter a valid credit card number.");
  /* NOTICE: Modified version of Castle.Components.Validator.CreditCardValidator
   * Redistributed under the Apache License 2.0 at http://www.apache.org/licenses/LICENSE-2.0
   * Valid Types: mastercard, visa, amex, dinersclub, enroute, discover, jcb, unknown, all (overrides all other settings)
   */

  $.validator.addMethod("creditcardtypes", function (value, element, param) {
    if (/[^0-9\-]+/.test(value)) {
      return false;
    }

    value = value.replace(/\D/g, "");
    var validTypes = 0x0000;

    if (param.mastercard) {
      validTypes |= 0x0001;
    }

    if (param.visa) {
      validTypes |= 0x0002;
    }

    if (param.amex) {
      validTypes |= 0x0004;
    }

    if (param.dinersclub) {
      validTypes |= 0x0008;
    }

    if (param.enroute) {
      validTypes |= 0x0010;
    }

    if (param.discover) {
      validTypes |= 0x0020;
    }

    if (param.jcb) {
      validTypes |= 0x0040;
    }

    if (param.unknown) {
      validTypes |= 0x0080;
    }

    if (param.all) {
      validTypes = 0x0001 | 0x0002 | 0x0004 | 0x0008 | 0x0010 | 0x0020 | 0x0040 | 0x0080;
    }

    if (validTypes & 0x0001 && (/^(5[12345])/.test(value) || /^(2[234567])/.test(value))) {
      // Mastercard
      return value.length === 16;
    }

    if (validTypes & 0x0002 && /^(4)/.test(value)) {
      // Visa
      return value.length === 16;
    }

    if (validTypes & 0x0004 && /^(3[47])/.test(value)) {
      // Amex
      return value.length === 15;
    }

    if (validTypes & 0x0008 && /^(3(0[012345]|[68]))/.test(value)) {
      // Dinersclub
      return value.length === 14;
    }

    if (validTypes & 0x0010 && /^(2(014|149))/.test(value)) {
      // Enroute
      return value.length === 15;
    }

    if (validTypes & 0x0020 && /^(6011)/.test(value)) {
      // Discover
      return value.length === 16;
    }

    if (validTypes & 0x0040 && /^(3)/.test(value)) {
      // Jcb
      return value.length === 16;
    }

    if (validTypes & 0x0040 && /^(2131|1800)/.test(value)) {
      // Jcb
      return value.length === 15;
    }

    if (validTypes & 0x0080) {
      // Unknown
      return true;
    }

    return false;
  }, "Please enter a valid credit card number.");
  /**
   * Validates currencies with any given symbols by @jameslouiz
   * Symbols can be optional or required. Symbols required by default
   *
   * Usage examples:
   *  currency: ["£", false] - Use false for soft currency validation
   *  currency: ["$", false]
   *  currency: ["RM", false] - also works with text based symbols such as "RM" - Malaysia Ringgit etc
   *
   *  <input class="currencyInput" name="currencyInput">
   *
   * Soft symbol checking
   *  currencyInput: {
   *     currency: ["$", false]
   *  }
   *
   * Strict symbol checking (default)
   *  currencyInput: {
   *     currency: "$"
   *     //OR
   *     currency: ["$", true]
   *  }
   *
   * Multiple Symbols
   *  currencyInput: {
   *     currency: "$,£,¢"
   *  }
   */

  $.validator.addMethod("currency", function (value, element, param) {
    var isParamString = typeof param === "string",
        symbol = isParamString ? param : param[0],
        soft = isParamString ? true : param[1],
        regex;
    symbol = symbol.replace(/,/g, "");
    symbol = soft ? symbol + "]" : symbol + "]?";
    regex = "^[" + symbol + "([1-9]{1}[0-9]{0,2}(\\,[0-9]{3})*(\\.[0-9]{0,2})?|[1-9]{1}[0-9]{0,}(\\.[0-9]{0,2})?|0(\\.[0-9]{0,2})?|(\\.[0-9]{1,2})?)$";
    regex = new RegExp(regex);
    return this.optional(element) || regex.test(value);
  }, "Please specify a valid currency");
  $.validator.addMethod("dateFA", function (value, element) {
    return this.optional(element) || /^[1-4]\d{3}\/((0?[1-6]\/((3[0-1])|([1-2][0-9])|(0?[1-9])))|((1[0-2]|(0?[7-9]))\/(30|([1-2][0-9])|(0?[1-9]))))$/.test(value);
  }, $.validator.messages.date);
  /**
   * Return true, if the value is a valid date, also making this formal check dd/mm/yyyy.
   *
   * @example $.validator.methods.date("01/01/1900")
   * @result true
   *
   * @example $.validator.methods.date("01/13/1990")
   * @result false
   *
   * @example $.validator.methods.date("01.01.1900")
   * @result false
   *
   * @example <input name="pippo" class="{dateITA:true}" />
   * @desc Declares an optional input element whose value must be a valid date.
   *
   * @name $.validator.methods.dateITA
   * @type Boolean
   * @cat Plugins/Validate/Methods
   */

  $.validator.addMethod("dateITA", function (value, element) {
    var check = false,
        re = /^\d{1,2}\/\d{1,2}\/\d{4}$/,
        adata,
        gg,
        mm,
        aaaa,
        xdata;

    if (re.test(value)) {
      adata = value.split("/");
      gg = parseInt(adata[0], 10);
      mm = parseInt(adata[1], 10);
      aaaa = parseInt(adata[2], 10);
      xdata = new Date(Date.UTC(aaaa, mm - 1, gg, 12, 0, 0, 0));

      if (xdata.getUTCFullYear() === aaaa && xdata.getUTCMonth() === mm - 1 && xdata.getUTCDate() === gg) {
        check = true;
      } else {
        check = false;
      }
    } else {
      check = false;
    }

    return this.optional(element) || check;
  }, $.validator.messages.date);
  $.validator.addMethod("dateNL", function (value, element) {
    return this.optional(element) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(value);
  }, $.validator.messages.date); // Older "accept" file extension method. Old docs: http://docs.jquery.com/Plugins/Validation/Methods/accept

  $.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
  }, $.validator.format("Please enter a value with a valid extension."));
  /**
   * Dutch giro account numbers (not bank numbers) have max 7 digits
   */

  $.validator.addMethod("giroaccountNL", function (value, element) {
    return this.optional(element) || /^[0-9]{1,7}$/.test(value);
  }, "Please specify a valid giro account number");
  $.validator.addMethod("greaterThan", function (value, element, param) {
    var target = $(param);

    if (this.settings.onfocusout && target.not(".validate-greaterThan-blur").length) {
      target.addClass("validate-greaterThan-blur").on("blur.validate-greaterThan", function () {
        $(element).valid();
      });
    }

    return value > target.val();
  }, "Please enter a greater value.");
  $.validator.addMethod("greaterThanEqual", function (value, element, param) {
    var target = $(param);

    if (this.settings.onfocusout && target.not(".validate-greaterThanEqual-blur").length) {
      target.addClass("validate-greaterThanEqual-blur").on("blur.validate-greaterThanEqual", function () {
        $(element).valid();
      });
    }

    return value >= target.val();
  }, "Please enter a greater value.");
  /**
   * IBAN is the international bank account number.
   * It has a country - specific format, that is checked here too
   *
   * Validation is case-insensitive. Please make sure to normalize input yourself.
   */

  $.validator.addMethod("iban", function (value, element) {
    // Some quick simple tests to prevent needless work
    if (this.optional(element)) {
      return true;
    } // Remove spaces and to upper case


    var iban = value.replace(/ /g, "").toUpperCase(),
        ibancheckdigits = "",
        leadingZeroes = true,
        cRest = "",
        cOperator = "",
        countrycode,
        ibancheck,
        charAt,
        cChar,
        bbanpattern,
        bbancountrypatterns,
        ibanregexp,
        i,
        p; // Check for IBAN code length.
    // It contains:
    // country code ISO 3166-1 - two letters,
    // two check digits,
    // Basic Bank Account Number (BBAN) - up to 30 chars

    var minimalIBANlength = 5;

    if (iban.length < minimalIBANlength) {
      return false;
    } // Check the country code and find the country specific format


    countrycode = iban.substring(0, 2);
    bbancountrypatterns = {
      "AL": "\\d{8}[\\dA-Z]{16}",
      "AD": "\\d{8}[\\dA-Z]{12}",
      "AT": "\\d{16}",
      "AZ": "[\\dA-Z]{4}\\d{20}",
      "BE": "\\d{12}",
      "BH": "[A-Z]{4}[\\dA-Z]{14}",
      "BA": "\\d{16}",
      "BR": "\\d{23}[A-Z][\\dA-Z]",
      "BG": "[A-Z]{4}\\d{6}[\\dA-Z]{8}",
      "CR": "\\d{17}",
      "HR": "\\d{17}",
      "CY": "\\d{8}[\\dA-Z]{16}",
      "CZ": "\\d{20}",
      "DK": "\\d{14}",
      "DO": "[A-Z]{4}\\d{20}",
      "EE": "\\d{16}",
      "FO": "\\d{14}",
      "FI": "\\d{14}",
      "FR": "\\d{10}[\\dA-Z]{11}\\d{2}",
      "GE": "[\\dA-Z]{2}\\d{16}",
      "DE": "\\d{18}",
      "GI": "[A-Z]{4}[\\dA-Z]{15}",
      "GR": "\\d{7}[\\dA-Z]{16}",
      "GL": "\\d{14}",
      "GT": "[\\dA-Z]{4}[\\dA-Z]{20}",
      "HU": "\\d{24}",
      "IS": "\\d{22}",
      "IE": "[\\dA-Z]{4}\\d{14}",
      "IL": "\\d{19}",
      "IT": "[A-Z]\\d{10}[\\dA-Z]{12}",
      "KZ": "\\d{3}[\\dA-Z]{13}",
      "KW": "[A-Z]{4}[\\dA-Z]{22}",
      "LV": "[A-Z]{4}[\\dA-Z]{13}",
      "LB": "\\d{4}[\\dA-Z]{20}",
      "LI": "\\d{5}[\\dA-Z]{12}",
      "LT": "\\d{16}",
      "LU": "\\d{3}[\\dA-Z]{13}",
      "MK": "\\d{3}[\\dA-Z]{10}\\d{2}",
      "MT": "[A-Z]{4}\\d{5}[\\dA-Z]{18}",
      "MR": "\\d{23}",
      "MU": "[A-Z]{4}\\d{19}[A-Z]{3}",
      "MC": "\\d{10}[\\dA-Z]{11}\\d{2}",
      "MD": "[\\dA-Z]{2}\\d{18}",
      "ME": "\\d{18}",
      "NL": "[A-Z]{4}\\d{10}",
      "NO": "\\d{11}",
      "PK": "[\\dA-Z]{4}\\d{16}",
      "PS": "[\\dA-Z]{4}\\d{21}",
      "PL": "\\d{24}",
      "PT": "\\d{21}",
      "RO": "[A-Z]{4}[\\dA-Z]{16}",
      "SM": "[A-Z]\\d{10}[\\dA-Z]{12}",
      "SA": "\\d{2}[\\dA-Z]{18}",
      "RS": "\\d{18}",
      "SK": "\\d{20}",
      "SI": "\\d{15}",
      "ES": "\\d{20}",
      "SE": "\\d{20}",
      "CH": "\\d{5}[\\dA-Z]{12}",
      "TN": "\\d{20}",
      "TR": "\\d{5}[\\dA-Z]{17}",
      "AE": "\\d{3}\\d{16}",
      "GB": "[A-Z]{4}\\d{14}",
      "VG": "[\\dA-Z]{4}\\d{16}"
    };
    bbanpattern = bbancountrypatterns[countrycode]; // As new countries will start using IBAN in the
    // future, we only check if the countrycode is known.
    // This prevents false negatives, while almost all
    // false positives introduced by this, will be caught
    // by the checksum validation below anyway.
    // Strict checking should return FALSE for unknown
    // countries.

    if (typeof bbanpattern !== "undefined") {
      ibanregexp = new RegExp("^[A-Z]{2}\\d{2}" + bbanpattern + "$", "");

      if (!ibanregexp.test(iban)) {
        return false; // Invalid country specific format
      }
    } // Now check the checksum, first convert to digits


    ibancheck = iban.substring(4, iban.length) + iban.substring(0, 4);

    for (i = 0; i < ibancheck.length; i++) {
      charAt = ibancheck.charAt(i);

      if (charAt !== "0") {
        leadingZeroes = false;
      }

      if (!leadingZeroes) {
        ibancheckdigits += "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ".indexOf(charAt);
      }
    } // Calculate the result of: ibancheckdigits % 97


    for (p = 0; p < ibancheckdigits.length; p++) {
      cChar = ibancheckdigits.charAt(p);
      cOperator = "" + cRest + "" + cChar;
      cRest = cOperator % 97;
    }

    return cRest === 1;
  }, "Please specify a valid IBAN");
  $.validator.addMethod("integer", function (value, element) {
    return this.optional(element) || /^-?\d+$/.test(value);
  }, "A positive or negative non-decimal number please");
  $.validator.addMethod("ipv4", function (value, element) {
    return this.optional(element) || /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value);
  }, "Please enter a valid IP v4 address.");
  $.validator.addMethod("ipv6", function (value, element) {
    return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
  }, "Please enter a valid IP v6 address.");
  $.validator.addMethod("lessThan", function (value, element, param) {
    var target = $(param);

    if (this.settings.onfocusout && target.not(".validate-lessThan-blur").length) {
      target.addClass("validate-lessThan-blur").on("blur.validate-lessThan", function () {
        $(element).valid();
      });
    }

    return value < target.val();
  }, "Please enter a lesser value.");
  $.validator.addMethod("lessThanEqual", function (value, element, param) {
    var target = $(param);

    if (this.settings.onfocusout && target.not(".validate-lessThanEqual-blur").length) {
      target.addClass("validate-lessThanEqual-blur").on("blur.validate-lessThanEqual", function () {
        $(element).valid();
      });
    }

    return value <= target.val();
  }, "Please enter a lesser value.");
  $.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
  }, "Letters only please");
  $.validator.addMethod("letterswithbasicpunc", function (value, element) {
    return this.optional(element) || /^[a-z\-.,()'"\s]+$/i.test(value);
  }, "Letters or punctuation only please"); // Limit the number of files in a FileList.

  $.validator.addMethod("maxfiles", function (value, element, param) {
    if (this.optional(element)) {
      return true;
    }

    if ($(element).attr("type") === "file") {
      if (element.files && element.files.length > param) {
        return false;
      }
    }

    return true;
  }, $.validator.format("Please select no more than {0} files.")); // Limit the size of each individual file in a FileList.

  $.validator.addMethod("maxsize", function (value, element, param) {
    if (this.optional(element)) {
      return true;
    }

    if ($(element).attr("type") === "file") {
      if (element.files && element.files.length) {
        for (var i = 0; i < element.files.length; i++) {
          if (element.files[i].size > param) {
            return false;
          }
        }
      }
    }

    return true;
  }, $.validator.format("File size must not exceed {0} bytes each.")); // Limit the size of all files in a FileList.

  $.validator.addMethod("maxsizetotal", function (value, element, param) {
    if (this.optional(element)) {
      return true;
    }

    if ($(element).attr("type") === "file") {
      if (element.files && element.files.length) {
        var totalSize = 0;

        for (var i = 0; i < element.files.length; i++) {
          totalSize += element.files[i].size;

          if (totalSize > param) {
            return false;
          }
        }
      }
    }

    return true;
  }, $.validator.format("Total size of all files must not exceed {0} bytes."));
  $.validator.addMethod("mobileNL", function (value, element) {
    return this.optional(element) || /^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)6((\s|\s?\-\s?)?[0-9]){8}$/.test(value);
  }, "Please specify a valid mobile number");
  $.validator.addMethod("mobileRU", function (phone_number, element) {
    var ruPhone_number = phone_number.replace(/\(|\)|\s+|-/g, "");
    return this.optional(element) || ruPhone_number.length > 9 && /^((\+7|7|8)+([0-9]){10})$/.test(ruPhone_number);
  }, "Please specify a valid mobile number");
  /* For UK phone functions, do the following server side processing:
   * Compare original input with this RegEx pattern:
   * ^\(?(?:(?:00\)?[\s\-]?\(?|\+)(44)\)?[\s\-]?\(?(?:0\)?[\s\-]?\(?)?|0)([1-9]\d{1,4}\)?[\s\d\-]+)$
   * Extract $1 and set $prefix to '+44<space>' if $1 is '44', otherwise set $prefix to '0'
   * Extract $2 and remove hyphens, spaces and parentheses. Phone number is combined $prefix and $2.
   * A number of very detailed GB telephone number RegEx patterns can also be found at:
   * http://www.aa-asterisk.org.uk/index.php/Regular_Expressions_for_Validating_and_Formatting_GB_Telephone_Numbers
   */

  $.validator.addMethod("mobileUK", function (phone_number, element) {
    phone_number = phone_number.replace(/\(|\)|\s+|-/g, "");
    return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(?:(?:(?:00\s?|\+)44\s?|0)7(?:[1345789]\d{2}|624)\s?\d{3}\s?\d{3})$/);
  }, "Please specify a valid mobile number");
  $.validator.addMethod("netmask", function (value, element) {
    return this.optional(element) || /^(254|252|248|240|224|192|128)\.0\.0\.0|255\.(254|252|248|240|224|192|128|0)\.0\.0|255\.255\.(254|252|248|240|224|192|128|0)\.0|255\.255\.255\.(254|252|248|240|224|192|128|0)/i.test(value);
  }, "Please enter a valid netmask.");
  /*
   * The NIE (Número de Identificación de Extranjero) is a Spanish tax identification number assigned by the Spanish
   * authorities to any foreigner.
   *
   * The NIE is the equivalent of a Spaniards Número de Identificación Fiscal (NIF) which serves as a fiscal
   * identification number. The CIF number (Certificado de Identificación Fiscal) is equivalent to the NIF, but applies to
   * companies rather than individuals. The NIE consists of an 'X' or 'Y' followed by 7 or 8 digits then another letter.
   */

  $.validator.addMethod("nieES", function (value, element) {
    "use strict";

    if (this.optional(element)) {
      return true;
    }

    var nieRegEx = new RegExp(/^[MXYZ]{1}[0-9]{7,8}[TRWAGMYFPDXBNJZSQVHLCKET]{1}$/gi);
    var validChars = "TRWAGMYFPDXBNJZSQVHLCKET",
        letter = value.substr(value.length - 1).toUpperCase(),
        number;
    value = value.toString().toUpperCase(); // Quick format test

    if (value.length > 10 || value.length < 9 || !nieRegEx.test(value)) {
      return false;
    } // X means same number
    // Y means number + 10000000
    // Z means number + 20000000


    value = value.replace(/^[X]/, "0").replace(/^[Y]/, "1").replace(/^[Z]/, "2");
    number = value.length === 9 ? value.substr(0, 8) : value.substr(0, 9);
    return validChars.charAt(parseInt(number, 10) % 23) === letter;
  }, "Please specify a valid NIE number.");
  /*
   * The Número de Identificación Fiscal ( NIF ) is the way tax identification used in Spain for individuals
   */

  $.validator.addMethod("nifES", function (value, element) {
    "use strict";

    if (this.optional(element)) {
      return true;
    }

    value = value.toUpperCase(); // Basic format test

    if (!value.match("((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)")) {
      return false;
    } // Test NIF


    if (/^[0-9]{8}[A-Z]{1}$/.test(value)) {
      return "TRWAGMYFPDXBNJZSQVHLCKE".charAt(value.substring(8, 0) % 23) === value.charAt(8);
    } // Test specials NIF (starts with K, L or M)


    if (/^[KLM]{1}/.test(value)) {
      return value[8] === "TRWAGMYFPDXBNJZSQVHLCKE".charAt(value.substring(8, 1) % 23);
    }

    return false;
  }, "Please specify a valid NIF number.");
  /*
   * Numer identyfikacji podatkowej ( NIP ) is the way tax identification used in Poland for companies
   */

  $.validator.addMethod("nipPL", function (value) {
    "use strict";

    value = value.replace(/[^0-9]/g, "");

    if (value.length !== 10) {
      return false;
    }

    var arrSteps = [6, 5, 7, 2, 3, 4, 5, 6, 7];
    var intSum = 0;

    for (var i = 0; i < 9; i++) {
      intSum += arrSteps[i] * value[i];
    }

    var int2 = intSum % 11;
    var intControlNr = int2 === 10 ? 0 : int2;
    return intControlNr === parseInt(value[9], 10);
  }, "Please specify a valid NIP number.");
  /**
   * Created for project jquery-validation.
   * @Description Brazillian PIS or NIS number (Número de Identificação Social Pis ou Pasep) is the equivalent of a
   * Brazilian tax registration number NIS of PIS numbers have 11 digits in total: 10 numbers followed by 1 check numbers
   * that are being used for validation.
   * @copyright (c) 21/08/2018 13:14, Cleiton da Silva Mendonça
   * @author Cleiton da Silva Mendonça <cleiton.mendonca@gmail.com>
   * @link http://gitlab.com/csmendonca Gitlab of Cleiton da Silva Mendonça
   * @link http://github.com/csmendonca Github of Cleiton da Silva Mendonça
   */

  $.validator.addMethod("nisBR", function (value) {
    var number;
    var cn;
    var sum = 0;
    var dv;
    var count;
    var multiplier; // Removing special characters from value

    value = value.replace(/([~!@#$%^&*()_+=`{}\[\]\-|\\:;'<>,.\/? ])+/g, ""); // Checking value to have 11 digits only

    if (value.length !== 11) {
      return false;
    } //Get check number of value


    cn = parseInt(value.substring(10, 11), 10); //Get number with 10 digits of the value

    number = parseInt(value.substring(0, 10), 10);

    for (count = 2; count < 12; count++) {
      multiplier = count;

      if (count === 10) {
        multiplier = 2;
      }

      if (count === 11) {
        multiplier = 3;
      }

      sum += number % 10 * multiplier;
      number = parseInt(number / 10, 10);
    }

    dv = sum % 11;

    if (dv > 1) {
      dv = 11 - dv;
    } else {
      dv = 0;
    }

    if (cn === dv) {
      return true;
    } else {
      return false;
    }
  }, "Please specify a valid NIS/PIS number");
  $.validator.addMethod("notEqualTo", function (value, element, param) {
    return this.optional(element) || !$.validator.methods.equalTo.call(this, value, element, param);
  }, "Please enter a different value, values must not be the same.");
  $.validator.addMethod("nowhitespace", function (value, element) {
    return this.optional(element) || /^\S+$/i.test(value);
  }, "No white space please");
  /**
  * Return true if the field value matches the given format RegExp
  *
  * @example $.validator.methods.pattern("AR1004",element,/^AR\d{4}$/)
  * @result true
  *
  * @example $.validator.methods.pattern("BR1004",element,/^AR\d{4}$/)
  * @result false
  *
  * @name $.validator.methods.pattern
  * @type Boolean
  * @cat Plugins/Validate/Methods
  */

  $.validator.addMethod("pattern", function (value, element, param) {
    if (this.optional(element)) {
      return true;
    }

    if (typeof param === "string") {
      param = new RegExp("^(?:" + param + ")$");
    }

    return param.test(value);
  }, "Invalid format.");
  /**
   * Dutch phone numbers have 10 digits (or 11 and start with +31).
   */

  $.validator.addMethod("phoneNL", function (value, element) {
    return this.optional(element) || /^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)[1-9]((\s|\s?\-\s?)?[0-9]){8}$/.test(value);
  }, "Please specify a valid phone number.");
  /**
   * Polish telephone numbers have 9 digits.
   *
   * Mobile phone numbers starts with following digits:
   * 45, 50, 51, 53, 57, 60, 66, 69, 72, 73, 78, 79, 88.
   *
   * Fixed-line numbers starts with area codes:
   * 12, 13, 14, 15, 16, 17, 18, 22, 23, 24, 25, 29, 32, 33,
   * 34, 41, 42, 43, 44, 46, 48, 52, 54, 55, 56, 58, 59, 61,
   * 62, 63, 65, 67, 68, 71, 74, 75, 76, 77, 81, 82, 83, 84,
   * 85, 86, 87, 89, 91, 94, 95.
   *
   * Ministry of National Defence numbers and VoIP numbers starts with 26 and 39.
   *
   * Excludes intelligent networks (premium rate, shared cost, free phone numbers).
   *
   * Poland National Numbering Plan http://www.itu.int/oth/T02020000A8/en
   */

  $.validator.addMethod("phonePL", function (phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    var regexp = /^(?:(?:(?:\+|00)?48)|(?:\(\+?48\)))?(?:1[2-8]|2[2-69]|3[2-49]|4[1-68]|5[0-9]|6[0-35-9]|[7-8][1-9]|9[145])\d{7}$/;
    return this.optional(element) || regexp.test(phone_number);
  }, "Please specify a valid phone number");
  /* For UK phone functions, do the following server side processing:
   * Compare original input with this RegEx pattern:
   * ^\(?(?:(?:00\)?[\s\-]?\(?|\+)(44)\)?[\s\-]?\(?(?:0\)?[\s\-]?\(?)?|0)([1-9]\d{1,4}\)?[\s\d\-]+)$
   * Extract $1 and set $prefix to '+44<space>' if $1 is '44', otherwise set $prefix to '0'
   * Extract $2 and remove hyphens, spaces and parentheses. Phone number is combined $prefix and $2.
   * A number of very detailed GB telephone number RegEx patterns can also be found at:
   * http://www.aa-asterisk.org.uk/index.php/Regular_Expressions_for_Validating_and_Formatting_GB_Telephone_Numbers
   */
  // Matches UK landline + mobile, accepting only 01-3 for landline or 07 for mobile to exclude many premium numbers

  $.validator.addMethod("phonesUK", function (phone_number, element) {
    phone_number = phone_number.replace(/\(|\)|\s+|-/g, "");
    return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(?:(?:(?:00\s?|\+)44\s?|0)(?:1\d{8,9}|[23]\d{9}|7(?:[1345789]\d{8}|624\d{6})))$/);
  }, "Please specify a valid uk phone number");
  /* For UK phone functions, do the following server side processing:
   * Compare original input with this RegEx pattern:
   * ^\(?(?:(?:00\)?[\s\-]?\(?|\+)(44)\)?[\s\-]?\(?(?:0\)?[\s\-]?\(?)?|0)([1-9]\d{1,4}\)?[\s\d\-]+)$
   * Extract $1 and set $prefix to '+44<space>' if $1 is '44', otherwise set $prefix to '0'
   * Extract $2 and remove hyphens, spaces and parentheses. Phone number is combined $prefix and $2.
   * A number of very detailed GB telephone number RegEx patterns can also be found at:
   * http://www.aa-asterisk.org.uk/index.php/Regular_Expressions_for_Validating_and_Formatting_GB_Telephone_Numbers
   */

  $.validator.addMethod("phoneUK", function (phone_number, element) {
    phone_number = phone_number.replace(/\(|\)|\s+|-/g, "");
    return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(?:(?:(?:00\s?|\+)44\s?)|(?:\(?0))(?:\d{2}\)?\s?\d{4}\s?\d{4}|\d{3}\)?\s?\d{3}\s?\d{3,4}|\d{4}\)?\s?(?:\d{5}|\d{3}\s?\d{3})|\d{5}\)?\s?\d{4,5})$/);
  }, "Please specify a valid phone number");
  /**
   * Matches US phone number format
   *
   * where the area code may not start with 1 and the prefix may not start with 1
   * allows '-' or ' ' as a separator and allows parens around area code
   * some people may want to put a '1' in front of their number
   *
   * 1(212)-999-2345 or
   * 212 999 2344 or
   * 212-999-0983
   *
   * but not
   * 111-123-5434
   * and not
   * 212 123 4567
   */

  $.validator.addMethod("phoneUS", function (phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(\+?1-?)?(\([2-9]([02-9]\d|1[02-9])\)|[2-9]([02-9]\d|1[02-9]))-?[2-9]\d{2}-?\d{4}$/);
  }, "Please specify a valid phone number");
  /*
  * Valida CEPs do brasileiros:
  *
  * Formatos aceitos:
  * 99999-999
  * 99.999-999
  * 99999999
  */

  $.validator.addMethod("postalcodeBR", function (cep_value, element) {
    return this.optional(element) || /^\d{2}.\d{3}-\d{3}?$|^\d{5}-?\d{3}?$/.test(cep_value);
  }, "Informe um CEP válido.");
  /**
   * Matches a valid Canadian Postal Code
   *
   * @example jQuery.validator.methods.postalCodeCA( "H0H 0H0", element )
   * @result true
   *
   * @example jQuery.validator.methods.postalCodeCA( "H0H0H0", element )
   * @result false
   *
   * @name jQuery.validator.methods.postalCodeCA
   * @type Boolean
   * @cat Plugins/Validate/Methods
   */

  $.validator.addMethod("postalCodeCA", function (value, element) {
    return this.optional(element) || /^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ] *\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i.test(value);
  }, "Please specify a valid postal code");
  /* Matches Italian postcode (CAP) */

  $.validator.addMethod("postalcodeIT", function (value, element) {
    return this.optional(element) || /^\d{5}$/.test(value);
  }, "Please specify a valid postal code");
  $.validator.addMethod("postalcodeNL", function (value, element) {
    return this.optional(element) || /^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/.test(value);
  }, "Please specify a valid postal code"); // Matches UK postcode. Does not match to UK Channel Islands that have their own postcodes (non standard UK)

  $.validator.addMethod("postcodeUK", function (value, element) {
    return this.optional(element) || /^((([A-PR-UWYZ][0-9])|([A-PR-UWYZ][0-9][0-9])|([A-PR-UWYZ][A-HK-Y][0-9])|([A-PR-UWYZ][A-HK-Y][0-9][0-9])|([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][A-HK-Y][0-9][ABEHMNPRVWXY]))\s?([0-9][ABD-HJLNP-UW-Z]{2})|(GIR)\s?(0AA))$/i.test(value);
  }, "Please specify a valid UK postcode");
  /*
   * Lets you say "at least X inputs that match selector Y must be filled."
   *
   * The end result is that neither of these inputs:
   *
   *	<input class="productinfo" name="partnumber">
   *	<input class="productinfo" name="description">
   *
   *	...will validate unless at least one of them is filled.
   *
   * partnumber:	{require_from_group: [1,".productinfo"]},
   * description: {require_from_group: [1,".productinfo"]}
   *
   * options[0]: number of fields that must be filled in the group
   * options[1]: CSS selector that defines the group of conditionally required fields
   */

  $.validator.addMethod("require_from_group", function (value, element, options) {
    var $fields = $(options[1], element.form),
        $fieldsFirst = $fields.eq(0),
        validator = $fieldsFirst.data("valid_req_grp") ? $fieldsFirst.data("valid_req_grp") : $.extend({}, this),
        isValid = $fields.filter(function () {
      return validator.elementValue(this);
    }).length >= options[0]; // Store the cloned validator for future validation

    $fieldsFirst.data("valid_req_grp", validator); // If element isn't being validated, run each require_from_group field's validation rules

    if (!$(element).data("being_validated")) {
      $fields.data("being_validated", true);
      $fields.each(function () {
        validator.element(this);
      });
      $fields.data("being_validated", false);
    }

    return isValid;
  }, $.validator.format("Please fill at least {0} of these fields."));
  /*
   * Lets you say "either at least X inputs that match selector Y must be filled,
   * OR they must all be skipped (left blank)."
   *
   * The end result, is that none of these inputs:
   *
   *	<input class="productinfo" name="partnumber">
   *	<input class="productinfo" name="description">
   *	<input class="productinfo" name="color">
   *
   *	...will validate unless either at least two of them are filled,
   *	OR none of them are.
   *
   * partnumber:	{skip_or_fill_minimum: [2,".productinfo"]},
   * description: {skip_or_fill_minimum: [2,".productinfo"]},
   * color:		{skip_or_fill_minimum: [2,".productinfo"]}
   *
   * options[0]: number of fields that must be filled in the group
   * options[1]: CSS selector that defines the group of conditionally required fields
   *
   */

  $.validator.addMethod("skip_or_fill_minimum", function (value, element, options) {
    var $fields = $(options[1], element.form),
        $fieldsFirst = $fields.eq(0),
        validator = $fieldsFirst.data("valid_skip") ? $fieldsFirst.data("valid_skip") : $.extend({}, this),
        numberFilled = $fields.filter(function () {
      return validator.elementValue(this);
    }).length,
        isValid = numberFilled === 0 || numberFilled >= options[0]; // Store the cloned validator for future validation

    $fieldsFirst.data("valid_skip", validator); // If element isn't being validated, run each skip_or_fill_minimum field's validation rules

    if (!$(element).data("being_validated")) {
      $fields.data("being_validated", true);
      $fields.each(function () {
        validator.element(this);
      });
      $fields.data("being_validated", false);
    }

    return isValid;
  }, $.validator.format("Please either skip these fields or fill at least {0} of them."));
  /* Validates US States and/or Territories by @jdforsythe
   * Can be case insensitive or require capitalization - default is case insensitive
   * Can include US Territories or not - default does not
   * Can include US Military postal abbreviations (AA, AE, AP) - default does not
   *
   * Note: "States" always includes DC (District of Colombia)
   *
   * Usage examples:
   *
   *  This is the default - case insensitive, no territories, no military zones
   *  stateInput: {
   *     caseSensitive: false,
   *     includeTerritories: false,
   *     includeMilitary: false
   *  }
   *
   *  Only allow capital letters, no territories, no military zones
   *  stateInput: {
   *     caseSensitive: false
   *  }
   *
   *  Case insensitive, include territories but not military zones
   *  stateInput: {
   *     includeTerritories: true
   *  }
   *
   *  Only allow capital letters, include territories and military zones
   *  stateInput: {
   *     caseSensitive: true,
   *     includeTerritories: true,
   *     includeMilitary: true
   *  }
   *
   */

  $.validator.addMethod("stateUS", function (value, element, options) {
    var isDefault = typeof options === "undefined",
        caseSensitive = isDefault || typeof options.caseSensitive === "undefined" ? false : options.caseSensitive,
        includeTerritories = isDefault || typeof options.includeTerritories === "undefined" ? false : options.includeTerritories,
        includeMilitary = isDefault || typeof options.includeMilitary === "undefined" ? false : options.includeMilitary,
        regex;

    if (!includeTerritories && !includeMilitary) {
      regex = "^(A[KLRZ]|C[AOT]|D[CE]|FL|GA|HI|I[ADLN]|K[SY]|LA|M[ADEINOST]|N[CDEHJMVY]|O[HKR]|PA|RI|S[CD]|T[NX]|UT|V[AT]|W[AIVY])$";
    } else if (includeTerritories && includeMilitary) {
      regex = "^(A[AEKLPRSZ]|C[AOT]|D[CE]|FL|G[AU]|HI|I[ADLN]|K[SY]|LA|M[ADEINOPST]|N[CDEHJMVY]|O[HKR]|P[AR]|RI|S[CD]|T[NX]|UT|V[AIT]|W[AIVY])$";
    } else if (includeTerritories) {
      regex = "^(A[KLRSZ]|C[AOT]|D[CE]|FL|G[AU]|HI|I[ADLN]|K[SY]|LA|M[ADEINOPST]|N[CDEHJMVY]|O[HKR]|P[AR]|RI|S[CD]|T[NX]|UT|V[AIT]|W[AIVY])$";
    } else {
      regex = "^(A[AEKLPRZ]|C[AOT]|D[CE]|FL|GA|HI|I[ADLN]|K[SY]|LA|M[ADEINOST]|N[CDEHJMVY]|O[HKR]|PA|RI|S[CD]|T[NX]|UT|V[AT]|W[AIVY])$";
    }

    regex = caseSensitive ? new RegExp(regex) : new RegExp(regex, "i");
    return this.optional(element) || regex.test(value);
  }, "Please specify a valid state"); // TODO check if value starts with <, otherwise don't try stripping anything

  $.validator.addMethod("strippedminlength", function (value, element, param) {
    return $(value).text().length >= param;
  }, $.validator.format("Please enter at least {0} characters"));
  $.validator.addMethod("time", function (value, element) {
    return this.optional(element) || /^([01]\d|2[0-3]|[0-9])(:[0-5]\d){1,2}$/.test(value);
  }, "Please enter a valid time, between 00:00 and 23:59");
  $.validator.addMethod("time12h", function (value, element) {
    return this.optional(element) || /^((0?[1-9]|1[012])(:[0-5]\d){1,2}(\ ?[AP]M))$/i.test(value);
  }, "Please enter a valid time in 12-hour am/pm format"); // Same as url, but TLD is optional

  $.validator.addMethod("url2", function (value, element) {
    return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
  }, $.validator.messages.url);
  /**
   * Return true, if the value is a valid vehicle identification number (VIN).
   *
   * Works with all kind of text inputs.
   *
   * @example <input type="text" size="20" name="VehicleID" class="{required:true,vinUS:true}" />
   * @desc Declares a required input element whose value must be a valid vehicle identification number.
   *
   * @name $.validator.methods.vinUS
   * @type Boolean
   * @cat Plugins/Validate/Methods
   */

  $.validator.addMethod("vinUS", function (v) {
    if (v.length !== 17) {
      return false;
    }

    var LL = ["A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"],
        VL = [1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 7, 9, 2, 3, 4, 5, 6, 7, 8, 9],
        FL = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2],
        rs = 0,
        i,
        n,
        d,
        f,
        cd,
        cdv;

    for (i = 0; i < 17; i++) {
      f = FL[i];
      d = v.slice(i, i + 1);

      if (i === 8) {
        cdv = d;
      }

      if (!isNaN(d)) {
        d *= f;
      } else {
        for (n = 0; n < LL.length; n++) {
          if (d.toUpperCase() === LL[n]) {
            d = VL[n];
            d *= f;

            if (isNaN(cdv) && n === 8) {
              cdv = LL[n];
            }

            break;
          }
        }
      }

      rs += d;
    }

    cd = rs % 11;

    if (cd === 10) {
      cd = "X";
    }

    if (cd === cdv) {
      return true;
    }

    return false;
  }, "The specified vehicle identification number (VIN) is invalid.");
  $.validator.addMethod("zipcodeUS", function (value, element) {
    return this.optional(element) || /^\d{5}(-\d{4})?$/.test(value);
  }, "The specified US ZIP Code is invalid");
  $.validator.addMethod("ziprange", function (value, element) {
    return this.optional(element) || /^90[2-5]\d\{2\}-\d{4}$/.test(value);
  }, "Your ZIP-code must be in the range 902xx-xxxx to 905xx-xxxx");
  return $;
});

(function () {
  $.validator.addMethod("completeUrl", function (value, element) {
    return /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/i.test(value);
  }, "Please enter a valid URL.");
  $.validator.addMethod("alpha", function (value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z]*$/);
  }, "Allow only alphabets.");
  $.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9]*$/);
  }, "Allow only alphanumeric.");
  $.validator.addMethod("alphaNumericDotSpaceHyphen", function (value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9.\- ]*$/);
  }, "Special Characters are not allowed.");
  $.validator.addMethod("validalphanumeric", function (value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z]*$/) || value == value.match(/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/);
  }, "Allow only enter alphanumeric but not allow only numbers.");
  $.validator.addMethod("alphanumeric_dash", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9.\-\_\s]+$/i.test(value);
  }, "Special characters are not allowed.");
  $.validator.addMethod("alphaNumericWithDot", function (value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9.]*$/);
  }, "Search text is either valid alpha numeric string or valid amount.");
  $.validator.addMethod("validNumber", function (value, element) {
    return this.optional(element) || /^[0-9.]+$/i.test(value);
  }, "Please enter valid value.");
  $.validator.addMethod("NumericWithPlus", function (value, element) {
    return this.optional(element) || value == value.match(/^[0-9+]*$/);
  }, "Only Numeric Value and Plus Symbol is allowed.");
  $.validator.addMethod("EMAIL", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,10}$/i.test(value);
  }, "Enter email address in proper format.");
  $.validator.addMethod("checkForWhiteSpace", function (value, element, param) {
    var val = $(element).val();

    if (val && $.trim(val) == '') {
      return false;
    }

    return true;
  }, "Only white spaces are not allowed.");
  $.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || element.files[0].size / 1024 <= param;
  }, 'File size must be less than {0}.');
  $.validator.addMethod("spaceNotAllowed", function (value, element) {
    return this.optional(element) || value == value.match(/^\S*$/);
  }, "Blank space are not allowed.");
  $.validator.addMethod("gst_number", function (value, element) {
    return this.optional(element) || /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
  }, "GST number is not in proper format.");
  $.validator.addMethod("pan_number", function (value, element) {
    return this.optional(element) || /^[A-Za-z]{5}[0-9]{4}[A-z]{1}$/i.test(value);
  }, "PAN number is not in proper format.");
  $.validator.addMethod("aadhar_number", function (value, element) {
    return this.optional(element) || /^\d{12}$/i.test(value);
  }, "Aadhar number is not in proper format.");
  $.validator.addMethod("valid_serial_key", function (value, element) {
    return this.optional(element) || /^[0-9]{2}[0|1](0[1-9]|1[0-2])(1?[6-9]|[2-9][0-9])[0-9]{6}$/i.test(value);
  }, "Please enter valid serial key.");
  $.validator.addMethod("lr_no", function (value, element) {
    return this.optional(element) || /^[0-9]{5}\/[A-Z]{1}[0-9]{1}\/20(1?[6-9]|[2-9][0-9])$/i.test(value);
  }, "Please enter valid LR number.");
  $.validator.addMethod("po_no", function (value, element) {
    if (value == 'VERBAL') {
      return true;
    } else if (this.optional(element) || /^(SI\/PG|SI\/PM)\/[0-9]{3,20}\/(1?[6-9]|[2-9][0-9])$/i.test(value)) {
      return true;
    } else {
      if (this.optional(element) || /^(SI\/PG|SI\/PM)\/[0-9]{3,20}\/(1?[6-9]|[2-9][0-9])\-(1?[6-9]|[2-9][0-9])$/i.test(value)) {
        var l = value.length;
        return value.substring(l - 2, l) - value.substring(l - 5, l - 3) == 1;
      }

      return false;
    }
  }, "Please enter valid PO number.");
  $.validator.addMethod("invoice_number", function (value, element) {
    return this.optional(element) || /^(SI\/PG|SI\/PM)\/[0-9]{5,20}\/20(1?[6-9]|[2-9][0-9])$/i.test(value);
  }, "Please enter valid invoice number.");
  $.validator.addMethod("vehicle_number", function (value, element) {
    return this.optional(element) || /^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}$/i.test(value);
  }, "Please enter valid vehicle number.");
  $.validator.addMethod("greater_than_number", function (value, element, options) {
    var $fields = $(options[1], element.form);
    var $fieldsFirst = $fields.eq(0);
    var validator = $fieldsFirst.data("valid_req_grp") ? $fieldsFirst.data("valid_req_grp") : $.extend({}, this);
    var val = 0;
    $(options[1], element.form).each(function () {
      if ($(this).val() == null || $(this).val() == undefined || $(this).val() == '') {
        val += 0;
      } else {
        val += parseInt($(this).val());
      }
    }); // Store the cloned validator for future validation

    $fieldsFirst.data("valid_req_grp", validator); // If element isn't being validated, run each require_from_group field's validation rules

    if (!$(element).data("being_validated")) {
      $fields.data("being_validated", true);
      $fields.each(function () {
        validator.element(this);
      });
      $fields.data("being_validated", false);
    }

    var valid = parseFloat(val) > parseFloat(options[0]);
    return valid;
  }, $.validator.format('One of these must bigger than {0}'));
  $.validator.addMethod("dimension", function (value, element) {
    return this.optional(element) || /^[0-9]{1,3}[xX]{1}[0-9]{1,3}[xX]{1}[0-9]{1,3}$/i.test(value);
  }, "Package dimension is not in proper format.");
  $.validator.addMethod("greater_than_zero", function (value, element) {
    return this.optional(element) || parseFloat(value) > 0;
  }, "Please enter greater than zero value.");
  $.validator.addMethod("comma_separated_emails", function (value, element) {
    if (this.optional(element)) {
      return true;
    }

    var mails = value.split(/,|;/);

    for (var i = 0; i < mails.length; i++) {
      // taken from the jquery validation internals
      // if (!/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(mails[i])) {
      if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/.test(mails[i])) {
        invalidEmails[i] = $.trim(mails[i]);
      }
    }

    if (invalidEmails.length > 0) {
      return false;
    }

    return true;
  }, function () {
    var invalid = invalidEmails.filter(String);
    invalidEmails = [];

    if (invalid.length > 1) {
      var message = invalid.join() + ' are not valid email address';
    } else if (invalid.length == 1) {
      var message = invalid.join() + ' is not valid email address';
    } else {
      var message = 'Please specify a valid email address or a comma separated list of addresses';
    }

    return message;
  });
})();

var invalidEmails = [];

var AmouneeApp = function () {
  $.fn.extend({
    animateCss: function animateCss(animationName, callback) {
      var animationEnd = function (el) {
        var animations = {
          animation: 'animationend',
          OAnimation: 'oAnimationEnd',
          MozAnimation: 'mozAnimationEnd',
          WebkitAnimation: 'webkitAnimationEnd'
        };

        for (var t in animations) {
          if (el.style[t] !== undefined) {
            return animations[t];
          }
        }
      }(document.createElement('div'));

      this.addClass('animated ' + animationName).one(animationEnd, function () {
        $(this).removeClass('animated ' + animationName);
        if (typeof callback === 'function') callback();
      });
      return this;
    }
  });

  var displaySidebarSelection = function displaySidebarSelection(data) {
    var pathname = window.location.href;
    pathname = pathname.replace(/\/$/, '').split('?')[0];
    $('li.menu-item a').each(function (index, element) {
      var link = $(element).attr('href');

      if (pathname == link) {
        if ($(element).parents('ul').hasClass('menu-subnav')) {
          $(element).parent('li').addClass('menu-item-active');
          $(element).parents('ul').parents('li').addClass('menu-item-open menu-item-here');
        } else {
          $(element).parent('li').addClass('menu-item-active');
        }
      }
    });
  };

  var setSidebarPref = function setSidebarPref() {
    $("#m_aside_left_minimize_toggle").click(function () {
      if ($(this).hasClass("m-brand__toggler--active")) {
        Cookies.set("is_sidebar_toggle", "false");
      } else {
        Cookies.set("is_sidebar_toggle", "true");
      }
    });
  };

  var setupAppDefaults = function setupAppDefaults() {
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };
    jQuery.validator.setDefaults({
      errorElement: 'div',
      //default input error message container
      errorClass: 'invalid-feedback',
      // default input error message class
      focusInvalid: false,
      // do not focus the last invalid input
      ignore: ":hidden, [contenteditable='true']:not([name])",
      errorPlacement: function errorPlacement(error, element) {
        // render error placement for each input type
        var group = $(element).closest('.m-form__group-sub').length > 0 ? $(element).closest('.m-form__group-sub') : $(element).closest('.form-group');
        var help = group.find('.m-form__help');
        $(element).addClass('is-invalid');

        if (group.find('.form-control-feedback').length !== 0) {
          return;
        }

        if (help.length > 0) {
          help.before(error);
        } else {
          if ($(element).closest('.input-group').length > 0) {
            $(element).closest('.input-group').after(error);
          } else if ($(element).hasClass("m-select2")) {
            $(element).parent().after(error);
          } else {
            if ($(element).is(':checkbox')) {
              $(element).closest('.m-checkbox').find('>span').after(error);
            } else {
              $(element).after(error);
            }
          }
        }
      },
      highlight: function highlight(element) {
        // hightlight error inputs
        $(element).addClass('is-invalid');
      },
      unhighlight: function unhighlight(element) {
        // revert the change done by hightlight
        var group = $(element).closest('.m-form__group-sub').length > 0 ? $(element).closest('.m-form__group-sub') : $(element).closest('.form-group');
        $(element).removeClass('is-invalid');
        group.find('.form-control-feedback').remove();
      },
      success: function success(label, element) {
        var group = $(label).closest('.m-form__group-sub').length > 0 ? $(label).closest('.m-form__group-sub') : $(label).closest('.form-group');
        $(element).removeClass('is-invalid');
        $(element).addClass('is-valid');
        group.find('.form-control-feedback').remove();
      }
    });
    $('form input').keypress(function (e) {
      if (e.which == 13) {
        if ($(this).closest('form').validate().form()) {
          if ($(this).closest('form').find('.pin-submit').prop("disabled") || $(this).closest('form').find('.pin-submit').is(':disabled')) {
            return false;
          }

          $(this).closest('form').find('.pin-submit').click();
        }

        return false;
      }
    });
    jQuery.each(["put", "delete"], function (i, method) {
      jQuery[method] = function (url, data, callback, type) {
        if (jQuery.isFunction(data)) {
          type = type || callback;
          callback = data;
          data = undefined;
        }

        return jQuery.ajax({
          url: url,
          type: method,
          dataType: type,
          data: data,
          success: callback
        });
      };
    });
  };

  var blankFunction = function blankFunction() {};

  return {
    //main function to initiate the module
    init: function init() {
      displaySidebarSelection();
      setSidebarPref();
      setupAppDefaults();
    },
    resetForm: function resetForm(form) {
      form.find('select').val('').trigger('change');
      form.trigger('reset');
      form.find("select,textarea, input").removeClass('is-valid');
      form.validate().resetForm();
    },
    disableButton: function disableButton(btn) {
      btn.attr('disabled', true);
    },
    disableButtonWithLoading: function disableButtonWithLoading(btn) {
      btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
    },
    enableButton: function enableButton(btn) {
      btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
    },
    showLoadingInButton: function showLoadingInButton(btn) {
      btn.addClass('m-loader m-loader--right m-loader--light');
    },
    removeLoadingFromButton: function removeLoadingFromButton(btn) {
      btn.removeClass('m-loader m-loader--right m-loader--light');
    },
    displayToastr: function displayToastr(type, data, callback) {
      toastr[type](data.message, data.title, {
        timeOut: 1500,
        onHidden: function onHidden() {
          callback();
        }
      });
    },
    displayToastrForSuccessAndFailure: function displayToastrForSuccessAndFailure(data, success_type, success, failure) {
      if (data.result == true) {
        this.displayToastr(success_type, data, success);
      } else {
        this.displayToastr('error', data, failure);
      }
    },
    displayResultAndReload: function displayResultAndReload(data) {
      this.displayToastrForSuccessAndFailure(data, 'info', function () {
        window.location.href = window.location.href;
      }, blankFunction);
    },
    displayResultAndRedirect: function displayResultAndRedirect(data, url) {
      this.displayToastrForSuccessAndFailure(data, 'info', function () {
        window.location.href = url;
      }, blankFunction);
    },
    displayFailedValidation: function displayFailedValidation(data) {
      if (data.status == 422) {
        $.each(data.responseJSON.errors, function (key, value) {
          for (var i = 0; i < value.length; i++) {
            toastr.error(value[i], 'Please Note!');
          }
        });
      } else if (data.status == 401) {
        toastr.error(data.responseJSON['message'], 'Attention!');
      } else {
        toastr.error(data.statusText, 'Attention!');
      }
    },
    displayResult: function displayResult(data) {
      this.displayToastrForSuccessAndFailure(data, 'info', blankFunction, blankFunction);
    },
    displayResultWithSuccessCallback: function displayResultWithSuccessCallback(data, callback) {
      this.displayToastrForSuccessAndFailure(data, 'success', callback, blankFunction);
    },
    displayResultWithCallback: function displayResultWithCallback(data, success, failure) {
      this.displayToastrForSuccessAndFailure(data, 'info', success, failure);
    },
    displayErrorMessage: function displayErrorMessage(msg) {
      toastr.error(msg, "Attention!", {
        timeOut: 2000
      });
    },
    displayResultForFailureWithCallback: function displayResultForFailureWithCallback(data, success, failure) {
      if (data.result == true) {
        success();
      } else {
        this.displayToastr('error', data, failure);
      }
    },
    blockUiAndButton: function blockUiAndButton(btn, el) {
      btn.attr('disabled', 'disabled');
      App.blockUI({
        target: el,
        textOnly: true
      });
    },
    unblockUiAndButton: function unblockUiAndButton(btn, el) {
      btn.removeAttr('disabled');
      App.unblockUI(el);
    },
    topFullAlert: function topFullAlert(result, title, message) {
      $("html, body").animate({
        scrollTop: 0
      }, "slow");

      if (result) {
        $(".pin-alert-box").removeClass("alert-info").addClass("alert-danger");
      } else {
        $(".pin-alert-box").removeClass("alert-danger").addClass("alert-info");
      }

      $(".pin-alert-box-title").html(title);
      $(".pin-alert-box-message").html(message);
      $(".pin-alert-box").show().animateCss('slideInDown');
    }
  };
}();

$(document).ready(function () {
  AmouneeApp.init();
  $('[data-toggle="tooltip"]').tooltip({
    container: 'body',
    trigger: 'hover'
  });
  $('select.m-select2').each(function () {
    var placeholder = $(this).data('placeholder') || 'Select an Option';

    if ($(this).attr('multiple') == 'multiple') {
      $(this).select2({
        placeholder: placeholder,
        closeOnSelect: true,
        maximumSelectionLength: 10,
        language: {
          maximumSelected: function maximumSelected(e) {
            var t = "You can only select " + e.maximum + " options";
            return t;
          }
        },
        allowClear: true
      });
    } else {
      $(this).select2({
        placeholder: placeholder
      });
    }
  });
  $('select.m-select2').change(function () {
    $(this).valid();
  });
  $(".input-daterange").datepicker({
    todayHighlight: true,
    format: 'dd/mm/yyyy',
    endDate: '0d'
  });
  $(".input-daterange input").each(function () {
    $(this).datepicker({
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      endDate: '0d'
    });
  });
  $(".pin-datepicker").datepicker({
    todayHighlight: true,
    format: 'dd/mm/yyyy' // orientation: "bottom"

  });
  $('.pin-alert-close').click(function () {
    $(".pin-alert-box").animateCss('slideOutUp', function () {
      $(".pin-alert-box").hide();
      $(".pin-alert-title").empty();
      $(".pin-alert-message").empty();
    });
  });
});

var TBHList = function () {
  return {
    init: function init() {},
    listButtonAjaxResultWithToastr: function listButtonAjaxResultWithToastr(data, btn) {
      AmouneeApp.displayResultWithCallback(data, function () {
        if ($(document).find(".pin-list-item").length == 1) {
          window.location.href = window.location.href;
        } else {
          btn.parentsUntil(".pin-list-item").parent().fadeOut(500).promise().done(function () {
            btn.parentsUntil(".pin-list-item").parent().remove();
          });
        }
      }, function () {
        AmouneeApp.enableButton(btn);
      });
    },
    commonSearchInList: function commonSearchInList() {
      var route = [location.protocol, '//', location.host, location.pathname].join('');
      route += "?records=" + $(".pin-common-records").val();

      if ($(".pin-common-search-text").val().trim() != '') {
        route += '&search=' + $(".pin-common-search-text").val().replace(/<[^>]+>/g, '').trim();
      }

      window.location.href = route;
    },
    clearCommonSearchInList: function clearCommonSearchInList() {
      var route = [location.protocol, '//', location.host, location.pathname].join('');
      route += "?records=" + $(".pin-common-records").val();
      window.location.href = route;
    },
    complexSearchInList: function complexSearchInList(form) {
      var route = [location.protocol, '//', location.host, location.pathname].join('');
      route += "?records=" + $(".pin-complex-records").val();
      form.find("input").each(function (index, field) {
        if ($(field).val().trim() != '' && $(field).val().trim() != null) {
          route += "&" + $(field).attr('name') + "=" + $(field).val().replace(/<[^>]+>/g, '').trim();
        }
      });
      form.find("select").each(function (index, field) {
        if ($(field).val() != '' && $(field).val() != null) {
          route += "&" + $(field).attr('name') + "=" + $(field).val();
        }
      });
      form.find("textarea").each(function (index, field) {
        if ($(field).val() != '' && $(field).val() != null) {
          route += "&" + $(field).attr('name') + "=" + $(field).val();
        }
      });
      window.location.href = route;
    },
    clearComplexSearchInList: function clearComplexSearchInList() {
      var route = [location.protocol, '//', location.host, location.pathname].join('');
      route += "?records=" + $(".pin-complex-records").val();
      window.location.href = route;
    },
    updateInListAndRemoveRow: function updateInListAndRemoveRow(btn) {
      AmouneeApp.disableButtonWithLoading(btn);
      $.post(btn.attr("data-action")).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          btn.closest(".pin-list-item").fadeOut(500).promise().done(function () {
            if ($(document).find(".pin-list-item").length == 1) {
              window.location.href = window.location.href;
            }

            btn.closest(".pin-list-item").remove();
          });
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    }
  };
}(); // The following function is used to reduce writing same functions to update our forms in the system


jQuery(document).on('click', '.pin-common-search-in-list-btn', function (e) {
  e.preventDefault(); // The following function is actually the calling function which performs the common search, if you have an instance
  // where you need to make changes in your search
  // write a new search function in your JS file but never override this function unless a security issue is found

  TBHList.commonSearchInList();
}); // The following function is used to reduce writing same functions to reset forms in our system

jQuery(document).on('click', '.pin-common-search-in-list-refresh', function (e) {
  e.preventDefault(); // The following calling function will clear and reset the page, but keep the number of records as it is

  TBHList.clearCommonSearchInList();
}); // The following function is used to reduce writing for lists with advanced search in the system

jQuery(document).on('click', '.pin-complex-search-in-list-btn', function (e) {
  e.preventDefault();
  var btn = $(".pin-complex-search-in-list-btn");
  var form = btn.closest('form'); // The following function is actually the calling function which performs the complex search having multiple variable information, if you have an instance
  // where you need to make changes in your search
  // write a new search function in your JS file but never override this function unless a security issue is found

  TBHList.complexSearchInList(form);
}); // The following function is used to reduce writing same functions to reset lists with advanced search in the system

jQuery(document).on('click', '.pin-complex-search-in-list-refresh', function (e) {
  e.preventDefault(); // The following calling function will clear and reset the page, but keep the number of records as it is

  TBHList.clearComplexSearchInList();
}); // The following function updates the common list search whenever the number of records in the list are updated

jQuery('.pin-common-records').select2({
  minimumResultsForSearch: -1
}).change(function () {
  TBHList.commonSearchInList();
}); // The following function updates the complex list with advanced search whenever the number of records in the list are updated

jQuery('.pin-complex-records').select2({
  minimumResultsForSearch: -1
}).change(function () {
  var btn = $(".pin-complex-search-in-list-btn");
  var form = btn.closest('form');
  TBHList.complexSearchInList(form);
});
jQuery(document).on('click', '.pin-common-process-list-btn', function (e) {
  e.preventDefault();
  var btn = $(this);
  TBHList.updateInListAndRemoveRow(btn);
});

var TBHForm = function () {
  return {
    init: function init() {},
    // Here we have given an advanced option, if they want to redirect then they just have to add the data-redirect-url
    // In the form, else if they keep it empty this will just scroll up to the top of the page
    // This we will do when we are giving demo to the client
    commonPostFormSubmit: function commonPostFormSubmit(form, btn) {
      // Checking whether form is valid for submission 
      if (!form.valid()) {
        return;
      } // Disabling button with Loading


      AmouneeApp.disableButtonWithLoading(btn); // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      $.post(form.attr("action"), form.serialize()).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          if (btn.data('additional-callback')) {
            var additional_callback = eval(btn.data('additional-callback'));

            if (typeof additional_callback == 'function') {
              additional_callback(data);
            }
          }

          if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
            window.location.href = form.attr("data-redirect-url");
          } else {
            AmouneeApp.resetForm(form);
            $('html, body').animate({
              scrollTop: 0
            }, 'slow');
          }

          AmouneeApp.enableButton(btn);
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    },
    commonUpdateFormSubmit: function commonUpdateFormSubmit(form, btn) {
      // Checking whether form is valid for submission 
      if (!form.valid()) {
        return;
      } // Disabling button with Loading


      AmouneeApp.disableButtonWithLoading(btn); // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      $.put(form.attr("action"), form.serialize()).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          if (btn.data('additional-callback')) {
            var additional_callback = eval(btn.data('additional-callback'));

            if (typeof additional_callback == 'function') {
              additional_callback(data);
            }
          }

          if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
            window.location.href = form.attr("data-redirect-url");
          } else {
            $('html, body').animate({
              scrollTop: 0
            }, 'slow');
          }

          AmouneeApp.enableButton(btn);
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    },
    multifileFormSubmit: function multifileFormSubmit(form, btn, method) {
      // Checking whether form is valid for submission 
      if (!form.valid()) {
        return;
      } // Disabling button with Loading


      AmouneeApp.disableButtonWithLoading(btn); // Adding files and serializing the form data

      var formData = new FormData();
      var submitedFiles = document.getElementsByClassName('form-files');

      var _iterator = _createForOfIteratorHelper(submitedFiles),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          item = _step.value;

          if (item.files && item.files[0]) {
            var extension = item.files[0].name.split('.');
            var temp2 = extension[extension.length - 1].toLowerCase();
            var size = parseFloat(item.files[0].size / 1024).toFixed(2);

            if (size > 2048) {
              toastr.warning("Maximum upload file size is 2MB", "Size Alert");
              return false;
            } else {
              formData.append(item.name, item.files[0]);
            }
          }
        } // Here we might face an issue when doing Multiple Select.
        // Please make sure to retest this when you are using it in a form with Multiple Selection and File Upload
        // The OR operator written here can be updated to AND operator
        // Make sure that the server side code can handle NULL values before doing that
        // The current system is already built and this code is better not altered

      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      form.serializeArray().forEach(function (field) {
        if (field.value.trim() != '' || field.value.trim() != null) {
          formData.append(field.name, field.value);
        }
      }); // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      $.ajax({
        url: form.attr("data-action"),
        type: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      });
    }
  };
}(); // The following function is used to reduce writing same functions to submit forms in our system


$(document).on('click', '.pin-common-submit', function (e) {
  e.preventDefault(); // The below lines are only for understanding for anyone using this library further

  var btn = $(this);
  var form = $(this).closest('form'); // Main function is called

  TBHForm.commonPostFormSubmit(form, btn);
}); // The following function is used to reduce writing same functions to update our forms in the system

$(document).on('click', '.pin-common-update', function (e) {
  e.preventDefault(); // The below lines are only for understanding for anyone using this library further

  var btn = $(this);
  var form = $(this).closest('form'); // Main function is called

  TBHForm.commonUpdateFormSubmit(form, btn);
}); // The following function is used to reduce writing same functions to reset forms in our system

$(document).on('click', '.pin-common-reset', function (e) {
  e.preventDefault(); // The below lines are only for understanding for anyone using this library further

  var form = $(this).closest('form'); // Main function is called

  AmouneeApp.resetForm(form);
  $('html, body').animate({
    scrollTop: 0
  }, 'slow');
}); // The following function is used to reduce writing the form submitting function in our system which includes multipart forms

$(document).on('click', '.pin-fwfile-submit', function (e) {
  e.preventDefault(); // The below lines are only for understanding for anyone using this library further

  var btn = $(this);
  var form = $(this).closest('form'); // Main function is called

  TBHForm.multifileFormSubmit(form, btn, 'POST');
});
$(document).on('click', '.pin-fwfile-update', function (e) {
  e.preventDefault(); // The below lines are only for understanding for anyone using this library further

  var btn = $(this);
  var form = $(this).closest('form'); // Main function is called

  TBHForm.multifileFormSubmit(form, btn, 'POST');
});

var TBHTable = function () {
  return {
    init: function init() {},
    updateWithAjax: function updateWithAjax(btn) {
      AmouneeApp.disableButtonWithLoading(btn);
      $.post(btn.attr("data-action")).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          AmouneeApp.enableButton(btn);
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    },
    updateWithAjaxAndRemoveRow: function updateWithAjaxAndRemoveRow(btn) {
      AmouneeApp.disableButtonWithLoading(btn);
      $.post(btn.attr("data-action")).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          btn.closest("tr").fadeOut(500).promise().done(function () {
            if ($(document).find(".pin-common-rows").length == 1) {
              btn.closest("tr").remove();
              window.location.href = window.location.href;
            } else {
              btn.parentsUntil(".pin-common-rows").parent().fadeOut(500).promise().done(function () {
                btn.closest("tr").remove();
              });
            }
          });
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    },
    deleteRowWithAjax: function deleteRowWithAjax(btn) {
      AmouneeApp.disableButtonWithLoading(btn);
      $["delete"](btn.attr("data-action")).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          btn.closest("tr").fadeOut(500).promise().done(function () {
            if ($(document).find(".pin-common-rows").length == 1) {
              btn.closest("tr").remove();
              window.location.href = window.location.href;
            } else {
              btn.parentsUntil(".pin-common-rows").parent().fadeOut(500).promise().done(function () {
                btn.closest("tr").remove();
                btn.parentsUntil(".pin-common-rows").parent().remove();
              });
            }
          });
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    }
  };
}();

jQuery(document).on('click', '.pin-common-delete-row', function (e) {
  e.preventDefault();
  var btn = $(this);
  TBHTable.deleteRowWithAjax(btn);
});
jQuery(document).on('click', '.pin-common-update-row', function (e) {
  e.preventDefault();
  var btn = $(this);
  TBHTable.updateWithAjax(btn);
});
jQuery(document).on('click', '.pin-common-process-row', function (e) {
  e.preventDefault();
  var btn = $(this);
  TBHTable.updateWithAjaxAndRemoveRow(btn);
});

var Authorization = function () {
  var handleUserBan = function handleUserBan() {
    $(document).on('click', '.pin-ban-user', function (e) {
      e.preventDefault();
      var btn = $(this);
      btn.tooltip('hide');
      AmouneeApp.disableButton(btn);
      $["delete"](btn.attr("data-action")).done(function (data) {
        TBHList.listButtonAjaxResultWithToastr(data, btn);
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  var handleUserUnban = function handleUserUnban() {
    $(document).on('click', '.pin-unban-user', function (e) {
      e.preventDefault();
      var btn = $(this);
      btn.tooltip('hide');
      AmouneeApp.disableButton(btn);
      $.post(btn.attr("data-action")).done(function (data) {
        TBHList.listButtonAjaxResultWithToastr(data, btn);
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  return {
    init: function init() {
      handleUserBan();
      handleUserUnban();
    }
  };
}();

jQuery(document).ready(function () {
  Authorization.init();
});

var Login = function () {
  var _login;

  var _showForm = function _showForm(form) {
    var cls = 'login-' + form + '-on';
    var form = 'login_' + form + '_form';

    _login.removeClass('login-forgot-on');

    _login.removeClass('login-signin-on');

    _login.addClass(cls);

    KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
  };

  var _handleSignInForm = function _handleSignInForm() {
    $('#kt_login_signin_submit').on('click', function (e) {
      e.preventDefault();
    }); // Handle forgot button

    $('#kt_login_forgot').on('click', function (e) {
      e.preventDefault();

      _showForm('forgot');

      clearForgotPasswordForm();
    });
  };

  var _handleForgotForm = function _handleForgotForm(e) {
    // Handle submit button
    $('#kt_login_forgot_submit').on('click', function (e) {
      e.preventDefault();
    }); // Handle cancel button

    $('#kt_login_forgot_cancel').on('click', function (e) {
      e.preventDefault();

      _showForm('signin');

      clearLoginForm();
    });
  };

  var clearLoginForm = function clearLoginForm() {
    AmouneeApp.resetForm($("#kt_login_signin_form")); // $("#kt_login_signin_form").clearForm();
    // $("#kt_login_signin_form").validate().resetForm();
  };

  var clearForgotPasswordForm = function clearForgotPasswordForm() {
    // $("#kt_login_forgot_form").clearForm();
    // $("#kt_login_forgot_form").validate().resetForm();
    AmouneeApp.resetForm($("#kt_login_forgot_form"));
  };

  var initSignInForm = function initSignInForm() {
    $("#kt_login_signin_form").validate({
      rules: {
        username: {
          required: true,
          checkForWhiteSpace: true
        },
        password: {
          required: true,
          checkForWhiteSpace: true
        }
      },
      messages: {
        username: {
          required: "Email Address is required."
        },
        password: {
          required: "Password is required."
        }
      }
    });
  };

  var initForgotPasswordForm = function initForgotPasswordForm() {
    $("#kt_login_forgot_form").validate({
      rules: {
        email: {
          required: true,
          EMAIL: true,
          checkForWhiteSpace: true
        }
      },
      messages: {
        email: {
          required: 'Recovery email address is required.',
          EMAIL: 'Please enter valid email address.'
        }
      }
    });
  };

  var handleSignInFormSubmit = function handleSignInFormSubmit() {
    $('#kt_login_signin_submit').click(function (e) {
      e.preventDefault();
      var btn = $(this);
      var form = $(this).closest('form');

      if (!form.valid()) {
        return;
      }

      AmouneeApp.disableButtonWithLoading(btn);
      $.post(form.attr("data-action"), form.serialize()).done(function (data) {
        AmouneeApp.removeLoadingFromButton(btn);
        AmouneeApp.displayResultWithCallback(data, function () {
          window.location.href = form.attr("data-redirect-url");
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  var handleForgetPasswordFormSubmit = function handleForgetPasswordFormSubmit() {
    $('#kt_login_forgot_submit').click(function (e) {
      e.preventDefault();
      var btn = $(this);
      var form = $(this).closest('form');

      if (!form.valid()) {
        return;
      }

      AmouneeApp.disableButtonWithLoading(btn);
      $.post(form.attr("data-action"), form.serialize()).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          clearForgotPasswordForm();
          AmouneeApp.enableButton(btn);

          _showForm('signin');
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  return {
    init: function init() {
      _login = $('#kt_login');

      _handleSignInForm();

      _handleForgotForm();

      initSignInForm();
      initForgotPasswordForm();
      handleSignInFormSubmit();
      handleForgetPasswordFormSubmit();
    }
  };
}();

jQuery(document).ready(function () {
  Login.init();
});

var resetPassword = function () {
  var initResetPasswordForm = function initResetPasswordForm() {
    $("#resetPasswordForm").validate({
      rules: {
        password: {
          required: true,
          minlength: 8,
          checkForWhiteSpace: true
        },
        password_confirmation: {
          required: true,
          equalTo: '#password',
          checkForWhiteSpace: true
        }
      },
      messages: {
        password: {
          required: "New password is required.",
          minlength: "Minimum length of password is 8"
        },
        password_confirmation: {
          required: "Password confirmation is required",
          equalTo: "Password should match"
        }
      }
    });
  };

  return {
    init: function init() {
      initResetPasswordForm();
    }
  };
}();

jQuery(document).ready(function () {
  resetPassword.init();
});

var ChangePassword = function () {
  var initChangePassword = function initChangePassword() {
    $("#changePasswordForm").validate({
      rules: {
        current_password: {
          required: true,
          checkForWhiteSpace: true
        },
        password: {
          required: true,
          minlength: 8,
          checkForWhiteSpace: true
        },
        password_confirmation: {
          required: true,
          equalTo: '#password',
          checkForWhiteSpace: true
        }
      },
      messages: {
        current_password: {
          required: "Current password is required."
        },
        password: {
          required: "New password is required.",
          minlength: "New password must be 8 characters long."
        },
        password_confirmation: {
          required: "Password confirmation is required.",
          equalTo: "Password should match."
        }
      }
    });
  };

  return {
    init: function init() {
      initChangePassword();
    }
  };
}();

jQuery(document).ready(function () {
  ChangePassword.init();
});

var GeneratePassword = function () {
  var openGeneratePasswordModal = function openGeneratePasswordModal() {
    $(document).on('click', '.pin-generate-password', function (e) {
      $("#generatePasswordForm").attr("data-action", $(this).attr("data-url"));
      $("#username").html($(this).attr("data-name"));
      $("#generatePasswordModal").modal('show');
    });
    $("#generatePasswordModal").on('hidden.bs.modal', function () {
      // $("#generatePasswordForm")[0].reset();
      // $("#generatePasswordForm").validate().resetForm();
      AmouneeApp.resetForm($("#generatePasswordForm"));
    });
  };

  var handleGeneratePassword = function handleGeneratePassword() {
    $("#generatePasswordForm").validate({
      rules: {
        password: {
          required: true,
          checkForWhiteSpace: true,
          spaceNotAllowed: true,
          rangelength: [8, 150]
        },
        password_confirmation: {
          required: true,
          equalTo: '#password',
          checkForWhiteSpace: true
        }
      },
      messages: {
        password: {
          required: "Password is required.",
          rangelength: "Password must be between 8 to 150 characters."
        },
        password_confirmation: {
          required: "Password confirmation is required.",
          equalTo: "Password should match."
        }
      }
    });
    $('#generatePasswordBtn').click(function (e) {
      e.preventDefault();
      var btn = $(this);
      var form = $("#generatePasswordForm");

      if (!form.valid()) {
        return;
      }

      AmouneeApp.disableButtonWithLoading(btn);
      $.post(form.attr("data-action"), form.serialize()).done(function (data) {
        AmouneeApp.displayResultWithCallback(data, function () {
          AmouneeApp.enableButton(btn); // $("#generatePasswordModal").modal("toggle");

          window.location.href = window.location.href;
        }, function () {
          AmouneeApp.enableButton(btn);
        });
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  return {
    init: function init() {
      openGeneratePasswordModal();
      handleGeneratePassword();
    }
  };
}();

jQuery(document).ready(function () {
  GeneratePassword.init();
});

var TeamMember = function () {
  var initTeamMemberCreateForm = function initTeamMemberCreateForm() {
    $("#profile_photo").fileinput({
      showUpload: false,
      showRemove: true,
      previewFileType: 'image',
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      msgPlaceholder: 'Select Profile Photo',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select Profile Photo"
    });
  };

  var initTeamMemberEditForm = function initTeamMemberEditForm() {
    var preview = ["no image"];
    var previewCaption = "Select Profile Photo";
    var broweseLabel = "Browse";
    var showRemove = true;

    if ($("#edit_profile_photo").is("[data-profile-photo-url]")) {
      preview = ["<img src='" + $("#edit_profile_photo").attr("data-profile-photo-url") + "' height='180px' width='180px'>"];
      previewCaption = "1 Profile photo uploaded";
      broweseLabel = "Change";
      showRemove = false;
    }

    $("#edit_profile_photo").fileinput({
      showUpload: false,
      showRemove: showRemove,
      previewFileType: 'image',
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      //maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      browseLabel: broweseLabel,
      browseClass: 'btn btn-sm btn-info m-btn--air',
      removeClass: 'btn btn-sm btn-danger m-btn--air',
      initialPreview: preview,
      initialCaption: previewCaption
    });
    $("#edit_profile_photo").trigger('fileclear');
  };

  var initTeamMemberValidation = function initTeamMemberValidation() {
    $("#TeamMemberForm").validate({
      rules: {
        first_name: {
          required: true,
          maxlength: 190,
          checkForWhiteSpace: true
        },
        last_name: {
          required: true,
          maxlength: 190,
          checkForWhiteSpace: true
        },
        middle_name: {
          maxlength: 190,
          checkForWhiteSpace: true
        },
        profile_photo: {
          filesize: 2048,
          accept: "image/jpg,jpeg,png",
          extension: "jpg|jpeg|png"
        },
        department: {
          required: true
        },
        designation: {
          required: true
        },
        password: {
          required: true,
          checkForWhiteSpace: true,
          spaceNotAllowed: true,
          rangelength: [8, 150]
        },
        password_confirmation: {
          required: true,
          equalTo: '#password',
          checkForWhiteSpace: true
        },
        employee_id: {
          required: true,
          checkForWhiteSpace: true
        },
        email: {
          required: true,
          EMAIL: true,
          checkForWhiteSpace: true
        },
        country_code: {
          // required: function(element){
          //     return $("input[name=phone_number]").val().trim()!="";
          // },
          required: true,
          checkForWhiteSpace: true,
          maxlength: [4],
          NumericWithPlus: true
        },
        phone_number: {
          required: true,
          checkForWhiteSpace: true,
          digits: true,
          rangelength: [10, 12]
        }
      },
      messages: {
        first_name: {
          required: "First name is required.",
          maxlength: "First Name exceeded maximum limit"
        },
        last_name: {
          required: "Last name is required.",
          maxlength: "Last Name exceeded maximum limit"
        },
        middle_name: {
          maxlength: "Middle Initial exceeded maximum limit"
        },
        profile_photo: {
          filesize: "Profile photo must be under 2 MB of size.",
          accept: "Please select JPG, JPEG or PNG file.",
          extension: "Profile photo must be in .jpg,.jpeg,.png format."
        },
        department: {
          required: "Department is required."
        },
        designation: {
          required: "Designation is required."
        },
        password: {
          required: "Password is required.",
          rangelength: "Password must be between 8 to 150 characters."
        },
        password_confirmation: {
          required: "Password confirmation is required.",
          equalTo: "Password should match."
        },
        employee_id: {
          required: "Employee ID is required.",
          checkForWhiteSpace: "Space not allowed."
        },
        email: {
          required: "Email is required.",
          EMAIL: "Enter email address in proper format."
        },
        country_code: {
          required: "Country code is Required.",
          maxlength: "Country code is invalid.",
          NumericWithPlus: "Enter a valid country code"
        },
        phone_number: {
          required: "Phone number is required.",
          digits: "Enter valid phone number.",
          rangelength: "Phone number must be between 10 to 12 digits."
        }
      }
    });
  };

  return {
    init: function init() {
      initTeamMemberCreateForm();
      initTeamMemberEditForm();
      initTeamMemberValidation();
    }
  };
}();

jQuery(document).ready(function () {
  TeamMember.init();
});

var Artisan = function () {
  var initArtisanCreateForm = function initArtisanCreateForm() {
    $("#vendor_picture").fileinput({
      showUpload: false,
      showRemove: true,
      previewFileType: 'image',
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      msgPlaceholder: 'Select Profile Photo',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select Profile Photo"
    });
    $("#passbook_picture").fileinput({
      showUpload: false,
      showRemove: true,
      showPreview: false,
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      msgPlaceholder: 'Select Passbook/Cheque Book',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select Passbook/Cheque Book"
    });
    $("#passbook_picture").change(function () {
      $(this).valid();
    });
    $("#artisan_cards").fileinput({
      showUpload: false,
      showRemove: true,
      showPreview: false,
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      msgPlaceholder: 'Select Artisan Card',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select Artisan Card"
    }); // $('#has_awards').click(function(){
    //     this.checked?$('#block').show():$('#block').hide(); //time for show
    // });

    $('#has_awards').click(function () {
      if (this.checked) {
        $('#block').show();
      } else {
        $('#block').hide();
        $('#awards').val(" ");
      }
    }); // $('#has_awards').click(function(){
    //    if($('#has_awards').prop('checked')==false)
    //    {
    //        $('#block').val(" ");
    //    }
    // });

    $("#id_proof").fileinput({
      showUpload: false,
      showRemove: true,
      showPreview: false,
      minFileCount: 1,
      maxFileCount: 2,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      msgPlaceholder: 'Select ID Proof',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select ID Proof"
    });
    $("#id_proof").change(function () {
      $(this).valid();
    });
  };

  var initArtisanEditForm = function initArtisanEditForm() {
    var preview = ["no image"];
    var previewCaption = "Select Profile Photo";
    var broweseLabel = "Browse";
    var showRemove = true;

    if ($("#edit_vendor_picture").is("[data-vendor-picture-url]")) {
      preview = ["<img src='" + $("#edit_vendor_picture").attr("data-vendor-picture-url") + "' height='180px' width='180px'>"];
      previewCaption = "1 Vendor Picture uploaded";
      broweseLabel = "Change";
      showRemove = false;
    }

    $("#edit_vendor_picture").fileinput({
      showUpload: false,
      showRemove: showRemove,
      previewFileType: 'image',
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      //maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      browseLabel: broweseLabel,
      browseClass: 'btn btn-sm btn-info m-btn--air',
      removeClass: 'btn btn-sm btn-danger m-btn--air',
      initialPreview: preview,
      initialCaption: previewCaption
    });
    $("#edit_vendor_picture").trigger('fileclear');
  };

  var handleArtisanStore = function handleArtisanStore() {
    $("#ArtisanForm").validate({
      rules: {
        vendor_picture: {
          filesize: 2048,
          accept: "image/jpg,jpeg,png",
          extension: "jpg|jpeg|png"
        },
        first_name: {
          required: true,
          maxlength: 190,
          checkForWhiteSpace: true
        },
        last_name: {
          required: true,
          maxlength: 190,
          checkForWhiteSpace: true
        },
        trade_name: {
          required: true,
          maxlength: 190
        },
        gst: {
          // digits: true,
          checkForWhiteSpace: true
        },
        category_id: {
          required: true
        },
        country_code: {
          required: true,
          checkForWhiteSpace: true,
          maxlength: [4],
          NumericWithPlus: true
        },
        phone_number: {
          required: true,
          checkForWhiteSpace: true,
          digits: true,
          rangelength: [10, 12]
        },
        email: {
          required: true,
          EMAIL: true,
          checkForWhiteSpace: true
        },
        street1: {
          required: true,
          maxlength: 190
        },
        street2: {
          required: true,
          maxlength: 190
        },
        zip: {
          required: true,
          maxlength: 20,
          digits: true
        },
        city: {
          required: true,
          maxlength: 20
        },
        state: {
          required: true,
          maxlength: 20
        },
        country: {
          required: true,
          maxlength: 20
        },
        'id_proof[]': {
          filesize: 2048,
          extension: "jpg|jpeg|png"
        },
        artisan_cards: {
          filesize: 2048,
          extension: "jpg|jpeg|png"
        },
        account_name: {
          required: true,
          maxlength: 190
        },
        account_number: {
          required: true,
          maxlength: 50,
          digits: true
        },
        bank_name: {
          required: true,
          maxlength: 190
        },
        ifsc: {
          required: true,
          maxlength: 30
        },
        commission: {
          digits: true
        },
        passbook_picture: {
          filesize: 2048,
          extension: "jpg|jpeg|png"
        }
      },
      messages: {
        vendor_picture: {
          filesize: "Vendor photo must be under 2 MB of size.",
          accept: "Please select JPG, JPEG or PNG file.",
          extension: "Vendor photo must be in .jpg,.jpeg,.png format."
        },
        category_id: {
          required: "Category is required."
        },
        first_name: {
          required: "First name is required.",
          maxlength: "First Name exceeded maximum limit"
        },
        last_name: {
          required: "Last name is required.",
          maxlength: "Last Name exceeded maximum limit"
        },
        trade_name: {
          required: "Trade name is required."
        },
        gst: {// digits: 'Only digits allowed.'
        },
        email: {
          required: "Email is required.",
          EMAIL: "Enter email address in proper format."
        },
        country_code: {
          required: "Country Code is Required.",
          maxlength: "Country Code is invalid.",
          NumericWithPlus: "Enter a valid country code"
        },
        phone_number: {
          required: "Phone number is required.",
          digits: "Enter valid phone number.",
          rangelength: "Phone number must be between 10 to 12 digits."
        },
        street1: {
          required: "Street 1 is required."
        },
        street2: {
          required: "Street 2 is required."
        },
        zip: {
          required: "Zip is required.",
          digits: "Only digits allowed."
        },
        city: {
          required: "City name is required."
        },
        state: {
          required: "State name is required."
        },
        country: {
          required: "Country is required."
        },
        'id_proof[]': {
          required: "ID proof is required.",
          filesize: "Id proof photos must be under 2 MB of size.",
          extension: "Id proof photos must be in .jpg,.jpeg,.png format."
        },
        artisan_cards: {
          filesize: "Artisan card must be under 2 MB of size.",
          extension: "Artisan card must be in .jpg,.jpeg,.png format."
        },
        account_name: {
          required: "Account Name is required."
        },
        account_number: {
          required: "Account Number is required.",
          digits: "Only digits allowed."
        },
        bank_name: {
          required: "Bank Name is required."
        },
        ifsc: {
          required: "IFSC is required.",
          maxlength: "Only upto 30 characters."
        },
        commission: {
          digits: "Only digits allowed."
        },
        passbook_picture: {
          required: "Passbook/Cheque book picture is required.",
          filesize: "Passbook/Cheque book photo must be under 2 MB of size.",
          extension: "Passbook/Cheque book photo must be in .jpg,.jpeg,.png format."
        }
      }
    });
    $('#addArtisanBtn').click(function (e) {
      var btn = $(this);
      var form = $(this).closest('form');

      if (!form.valid()) {
        $('#is_approved').val('0');
        return;
      }

      var formData = new FormData();
      var submitedFiles = document.getElementsByClassName('form-files');

      var _iterator2 = _createForOfIteratorHelper(submitedFiles),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          item = _step2.value;

          if (item.files && item.files[0]) {
            var extension = item.files[0].name.split('.');
            var temp2 = extension[extension.length - 1].toLowerCase();
            var size = parseFloat(item.files[0].size / 1024).toFixed(2);

            if (size > 2048) {
              toastr.warning("Maximum upload file size is 2MB", "Size Alert");
              return false;
            } else {
              formData.append(item.name, item.files[0]);
            }
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }

      var totalfiles = document.getElementById('id_proof').files.length;

      for (var index = 0; index < totalfiles; index++) {
        var item = document.getElementById('id_proof');
        var extension = item.files[0].name.split('.');
        var temp2 = extension[extension.length - 1].toLowerCase();
        var size = parseFloat(item.files[0].size / 1024).toFixed(2);

        if (size > 2048) {
          toastr.warning("Maximum upload file size is 2MB", "Size Alert");
          return false;
        } else {
          formData.append(item.name, document.getElementById('id_proof').files[index]);
        }
      }

      form.serializeArray().forEach(function (field) {
        if (field.value.trim() != '' || field.value.trim() != null) {
          formData.append(field.name, field.value);
        }
      }); // console.log(formData);
      // return false;
      // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      AmouneeApp.disableButtonWithLoading(btn);
      $.ajax({
        url: form.attr("data-action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      }); // Disabling button with Loading

      AmouneeApp.disableButtonWithLoading(btn);
    });
    $('#addArtisanApproveBtn').click(function (e) {
      $('#is_approved').val('1');
      $('#addArtisanBtn').trigger('click');
    });
  };

  var handleArtisanApproval = function handleArtisanApproval() {
    $("#artisanApprovalForm").validate({
      rules: {
        approval_note: {
          required: true,
          maxlength: 191,
          checkForWhiteSpace: true
        }
      },
      messages: {
        approval_note: {
          required: "Approval note is required.",
          maxlength: "Approval exceeded maximum limit of 191 characters."
        }
      }
    });
    $('button.approve-artisan-btn').click(function () {
      $('#artisanApprovalForm').attr('action', $(this).attr('data-action'));
    });
  };

  var handleArtisanApprovalModal = function handleArtisanApprovalModal() {
    $('#approveModal').on('hidden.bs.modal', function () {
      AmouneeApp.resetForm($('#artisanApprovalForm'));
    });
  };

  var handleArtisanRejection = function handleArtisanRejection() {
    $("#artisanRejectionForm").validate({
      rules: {
        rejection_note: {
          required: true,
          maxlength: 191,
          checkForWhiteSpace: true
        }
      },
      messages: {
        rejection_note: {
          required: "Rejection note is required.",
          maxlength: "Rejection exceeded maximum limit of 191 characters."
        }
      }
    });
    $('button.reject-artisan-btn').click(function () {
      $('#artisanRejectionForm').attr('action', $(this).attr('data-action'));
    });
  };

  var handleArtisanRejectionModal = function handleArtisanRejectionModal() {
    $('#rejectModal').on('hidden.bs.modal', function () {
      AmouneeApp.resetForm($('#artisanRejectionForm'));
    });
  };

  var handleArtisanUpdate = function handleArtisanUpdate() {
    $('#updateArtisanBtn').click(function (e) {
      var btn = $(this);
      var form = $(this).closest('form');

      if (!form.valid()) {
        return;
      }

      var formData = new FormData();
      var submitedFiles = document.getElementsByClassName('form-files');

      var _iterator3 = _createForOfIteratorHelper(submitedFiles),
          _step3;

      try {
        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          item = _step3.value;

          if (item.files && item.files[0]) {
            var extension = item.files[0].name.split('.');
            var temp2 = extension[extension.length - 1].toLowerCase();
            var size = parseFloat(item.files[0].size / 1024).toFixed(2);

            if (size > 2048) {
              toastr.warning("Maximum upload file size is 2MB", "Size Alert");
              return false;
            } else {
              formData.append(item.name, item.files[0]);
            }
          }
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }

      var totalfiles = document.getElementById('id_proof').files.length;

      for (var index = 0; index < totalfiles; index++) {
        var item = document.getElementById('id_proof');
        var extension = item.files[0].name.split('.');
        var temp2 = extension[extension.length - 1].toLowerCase();
        var size = parseFloat(item.files[0].size / 1024).toFixed(2);

        if (size > 2048) {
          toastr.warning("Maximum upload file size is 2MB", "Size Alert");
          return false;
        } else {
          formData.append(item.name, document.getElementById('id_proof').files[index]);
        }
      }

      form.serializeArray().forEach(function (field) {
        if (field.value.trim() != '' || field.value.trim() != null) {
          formData.append(field.name, field.value);
        }
      }); // console.log(formData);
      // return false;
      // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      AmouneeApp.disableButtonWithLoading(btn);
      $.ajax({
        url: form.attr("data-action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      }); // Disabling button with Loading

      AmouneeApp.disableButtonWithLoading(btn);
    });
  };

  var handleStoreImport = function handleStoreImport() {
    $("#importForm").validate({
      rules: {
        file: {
          required: true,
          extension: "xls|xlsx|xlsm|csv"
        }
      },
      messages: {
        file: {
          required: "File is required",
          extension: "Please select Xls, Xlsx , Xlsm or CSV file"
        }
      }
    });
    $('button.import-artisan').click(function () {
      e.preventDefault();
      var form = $('#importForm');
      var btn = $(this);

      if (!form.valid()) {
        return;
      }

      AmouneeApp.disableButtonWithLoading(btn);
      var formData = new FormData();
      var files = $('#file')[0].files[0];
      formData.append('file', files);
      $.ajax({
        url: form.attr("action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      });
    });
  };

  var handleStoreImportModal = function handleStoreImportModal() {
    // $(document).on('click', '.import-btn', function (e) {
    //     $("#importForm").attr("data-action",$(this).attr("data-action"));
    //     $("#importModal").modal('show');
    // });
    $(".import-btn").click(function () {
      var btn = $(this);
      var artisan_import_url = btn.attr("data-action");
      var redirect_url = btn.attr("data-redirect-url");
      $("#importForm").attr("action", artisan_import_url);
      $("#importForm").attr("data-redirect-url", redirect_url);
    });
    $('#importModal').on('hidden.bs.modal', function () {
      AmouneeApp.resetForm($('#importForm'));
    });
  };

  return {
    init: function init() {
      initArtisanCreateForm();
      initArtisanEditForm();
      handleArtisanStore();
      handleArtisanApproval();
      handleArtisanApprovalModal();
      handleArtisanRejection();
      handleArtisanRejectionModal();
      handleArtisanUpdate();
      handleStoreImport();
      handleStoreImportModal();
    }
  };
}();

jQuery(document).ready(function () {
  Artisan.init();
});

var Category = function () {
  var handleStoreCommission = function handleStoreCommission() {
    $("#commissionForm").validate({
      rules: {
        commission: {
          digits: true,
          maxlength: 191,
          checkForWhiteSpace: true
        }
      },
      messages: {
        commission: {
          digits: "Only digits allowed.",
          maxlength: "Approval exceeded maximum limit of 191 characters.",
          checkForWhiteSpace: "Blank spaces not allowed."
        }
      }
    });
    $('button.commission-btn').click(function () {
      $('#commissionForm').attr('action', $(this).attr('data-action'));
      AmouneeApp.disableButtonWithLoading(btn);
      $.ajax({
        url: form.attr("data-action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      }); // Disabling button with Loading

      AmouneeApp.disableButtonWithLoading(btn);
    });
  };

  var handleStoreCommissionModal = function handleStoreCommissionModal() {
    $(document).on('click', '.commission-btn', function (e) {
      $("#commissionForm").attr("data-action", $(this).attr("data-url"));
      $("#catname").html($(this).attr("data-name"));
      $("#commissionModal").modal('show');
    });
    $('#commissionModal').on('hidden.bs.modal', function () {
      AmouneeApp.resetForm($('#commissionForm'));
    });
  };

  return {
    init: function init() {
      handleStoreCommissionModal();
      handleStoreCommission();
    }
  };
}();

jQuery(document).ready(function () {
  Category.init();
});

var Product = function () {
  var handleManageStock = function handleManageStock() {
    $("#manageStockForm").validate({
      rules: {
        stock_status: {
          required: true
        },
        stock: {
          digits: true
        }
      },
      messages: {
        stock_status: {
          required: "Stock status is required."
        },
        stock: {
          digits: "Only digits allowed."
        }
      }
    });
    $('#stock_status').change(function () {
      var stock_status = $("#stock_status").val();

      if (stock_status == 'In stock') {
        $('#stock_val').show();
      } else {
        $('#stock').val("");
        $('#stock_val').hide();
      }
    });
    $('button.manage-stock').click(function () {
      e.preventDefault();
      var form = $('#manageStockForm');
      var btn = $(this);

      if (!form.valid()) {
        return;
      }

      AmouneeApp.disableButtonWithLoading(btn);
      var formData = new FormData();
      var files = $('#file')[0].files[0];
      formData.append('file', files);
      $.ajax({
        url: form.attr("action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      });
    });
  };

  var initProductCreateForm = function initProductCreateForm() {
    $('.amounee-editor').summernote({
      height: 200,
      toolbar: [['style', ['style']], ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']], ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['para', ['ol', 'ul', 'paragraph', 'height']], ['view', ['undo', 'redo', 'fullscreen']]]
    });
    $("#product_image").fileinput({
      showUpload: false,
      showRemove: true,
      showPreview: false,
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Please select JPG, JPEG or PNG file.',
      msgPlaceholder: 'Select Product Image',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select Product Image"
    });
    $("#product_image").change(function () {
      $(this).valid();
    });
    $("#product_gallery").fileinput({
      showUpload: false,
      showRemove: true,
      showPreview: false,
      minFileCount: 1,
      maxFileCount: 7,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      msgErrorClass: 'file-error-message d-none',
      minFileSize: 1,
      maxFileSize: 2000,
      msgInvalidFileExtension: 'Invalid File Format',
      msgPlaceholder: 'Select images',
      browseLabel: 'Browse',
      browseClass: 'btn btn-sm btn-info',
      removeClass: 'btn btn-sm btn-danger',
      initialPreview: ["no image"],
      initialCaption: "Select Images"
    });
    $("#product_gallery").change(function () {
      $(this).valid();
    });
    $("#base_price").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) //prouduct level commission exist
        {
          commission = parseInt(commission);
          base_price = parseInt(base_price);

          if (commission_unit == 'rupee') {
            var sales_price = base_price + commission;
            $('#sales_price').val(sales_price);
          } else if (commission_unit == 'percentage') {
            var tmp = commission * base_price / 100;
            var sales_price = base_price + tmp;
            $('#sales_price').val(sales_price);
          } else {
            $('#sales_price').val('');
          }
        } else //check for sub category level , artisan level and global level commission
        {
          //code for fetching subcategory level commission
          var sub_category_commission;
          var sales_price = parseInt(base_price);
          var id = $("#sub_category_id").val();

          if (id) {
            $.ajax({
              url: 'http://amounee.test/category/' + id + '/details',
              method: 'get',
              async: false,
              success: function success(res) {
                sub_category_commission = parseInt(res.commission);
                sub_category_commission = sub_category_commission * base_price / 100;

                if (sub_category_commission) {
                  sales_price += sub_category_commission;
                }

                $('#sales_price').val(sales_price);
              }
            });
          }

          if (!sub_category_commission) {
            //check for artisan level commission
            var artisan_commission;
            var artisan_id = $('#artisan_id').val();

            if (artisan_id) {
              $.ajax({
                url: 'http://amounee.test/artisan/' + artisan_id + '/details',
                method: 'get',
                async: false,
                success: function success(res) {
                  artisan_commission = parseInt(res.commission);
                  artisan_commission = artisan_commission * base_price / 100;

                  if (artisan_commission) {
                    sales_price += artisan_commission;
                  }

                  $('#sales_price').val(sales_price);
                }
              });
            }
          } //global level commission


          if (base_price == sales_price) {
            var sales_price;
            commission = 25 * base_price / 100;
            sales_price += commission;
            $('#sales_price').val(sales_price);
          }
        }
    });
    $("#commission").change(function () {
      var base_price = $('#base_price').val();

      if (!base_price) {
        base_price = 0;
      }

      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) {
        commission = parseInt(commission);
        base_price = parseInt(base_price);

        if (commission_unit == 'rupee') {
          var sales_price = base_price + commission;
          $('#sales_price').val(sales_price);
        } else if (commission_unit == 'percentage') {
          var tmp = commission * base_price / 100;
          var sales_price = base_price + tmp;
          $('#sales_price').val(sales_price);
        } else {
          $('#sales_price').val('');
        }
      } else {
        var sales_price = parseInt(base_price);
      }

      $('#sales_price').val(sales_price);
    });
    $("#commission_unit").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $(this).val();

      if (commission) {
        commission = parseInt(commission);
        base_price = parseInt(base_price);

        if (commission_unit == 'rupee') {
          var sales_price = base_price + commission;
          $('#sales_price').val(sales_price);
        } else if (commission_unit == 'percentage') {
          var tmp = commission * base_price / 100;
          var sales_price = base_price + tmp;
          $('#sales_price').val(sales_price);
        } else {
          $('#sales_price').val('');
        }
      } else {
        var sales_price = parseInt(base_price);
      }

      $('#sales_price').val(sales_price);
    });
    $("#artisan_id").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) //prouduct level commission exist
        {
          commission = parseInt(commission);
          base_price = parseInt(base_price);

          if (commission_unit == 'rupee') {
            var sales_price = base_price + commission;
            $('#sales_price').val(sales_price);
          } else if (commission_unit == 'percentage') {
            var tmp = commission * base_price / 100;
            var sales_price = base_price + tmp;
            $('#sales_price').val(sales_price);
          } else {
            $('#sales_price').val('');
          }
        } else //check for sub category level , artisan level and global level commission
        {
          //code for fetching subcategory level commission
          var sub_category_commission;
          var sales_price = parseInt(base_price);
          var id = $("#sub_category_id").val();

          if (id) {
            $.ajax({
              url: 'http://amounee.test/category/' + id + '/details',
              method: 'get',
              async: false,
              success: function success(res) {
                sub_category_commission = parseInt(res.commission);
                sub_category_commission = sub_category_commission * base_price / 100;

                if (sub_category_commission) {
                  sales_price += sub_category_commission;
                }

                $('#sales_price').val(sales_price);
              }
            });
          }

          if (!sub_category_commission) {
            //check for artisan level commission
            var artisan_commission;
            var artisan_id = $('#artisan_id').val();

            if (artisan_id) {
              $.ajax({
                url: 'http://amounee.test/artisan/' + artisan_id + '/details',
                method: 'get',
                async: false,
                success: function success(res) {
                  artisan_commission = parseInt(res.commission);
                  artisan_commission = artisan_commission * base_price / 100;

                  if (artisan_commission) {
                    sales_price += artisan_commission;
                  }

                  $('#sales_price').val(sales_price);
                }
              });
            }
          } //global level commission


          if (base_price == sales_price) {
            var sales_price;
            commission = 25 * base_price / 100;
            sales_price += commission;
            $('#sales_price').val(sales_price);
          }
        }
    });
    $("#sub_category_id").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) //prouduct level commission exist
        {
          commission = parseInt(commission);
          base_price = parseInt(base_price);

          if (commission_unit == 'rupee') {
            var sales_price = base_price + commission;
            $('#sales_price').val(sales_price);
          } else if (commission_unit == 'percentage') {
            var tmp = commission * base_price / 100;
            var sales_price = base_price + tmp;
            $('#sales_price').val(sales_price);
          } else {
            $('#sales_price').val('');
          }
        } else //check for sub category level , artisan level and global level commission
        {
          //code for fetching subcategory level commission
          var artisan_commission;
          var sub_category_commission;
          var sales_price = parseInt(base_price);
          var id = $("#sub_category_id").val();

          if (id) {
            $.ajax({
              url: 'http://amounee.test/category/' + id + '/details',
              method: 'get',
              async: false,
              success: function success(res) {
                sub_category_commission = parseInt(res.commission);
                sub_category_commission = sub_category_commission * base_price / 100;

                if (sub_category_commission) {
                  sales_price += sub_category_commission;
                }

                $('#sales_price').val(sales_price);
              }
            });
          }

          if (!sub_category_commission) {
            //check for artisan level commission
            var sales_price = parseInt(base_price);
            var artisan_id = $('#artisan_id').val();

            if (artisan_id) {
              $.ajax({
                url: 'http://amounee.test/artisan/' + artisan_id + '/details',
                method: 'get',
                async: false,
                success: function success(res) {
                  artisan_commission = parseInt(res.commission);
                  artisan_commission = artisan_commission * base_price / 100;

                  if (artisan_commission) {
                    sales_price += artisan_commission;
                  }

                  $('#sales_price').val(sales_price);
                }
              });
            }
          } //global level commission


          if (base_price == sales_price) {
            var sales_price;
            commission = 25 * base_price / 100;
            sales_price += commission;
            $('#sales_price').val(sales_price);
          }
        }
    });
    $('#stock_status').change(function () {
      var stock_status = $("#stock_status").val();

      if (stock_status == 'In stock') {
        $('#stock_div').show();
      } else {
        $('#stock').val("");
        $('#stock_div').hide();
      }
    });
  };

  var handleProductStore = function handleProductStore() {
    $("#ProductForm").validate({
      rules: {
        product_name: {
          required: true,
          checkForWhiteSpace: true
        },
        sku: {
          // required: true,
          checkForWhiteSpace: true
        },
        artisan_id: {
          required: true
        },
        stock_status: {
          required: true,
          checkForWhiteSpace: true
        },
        stock: {
          required: function required(element) {
            return $("#stock_status").val() == "In stock";
          },
          digits: true,
          checkForWhiteSpace: true
        },
        category_id: {
          required: true
        },
        sub_category_id: {
          required: true
        },
        base_price: {
          digits: true,
          required: true,
          checkForWhiteSpace: true
        },
        product_image: _defineProperty({
          filesize: 2048,
          accept: "image/jpg,jpeg,png",
          extension: "jpg|jpeg|png"
        }, "filesize", 2048),
        hsn_code: {
          required: true,
          checkForWhiteSpace: true,
          greater_than_zero: true
        },
        commission: {
          checkForWhiteSpace: true,
          validNumber: true
        },
        commission_unit: {
          required: function required(element) {
            return $("#commission").val().trim() != "";
          }
        },
        material: {
          checkForWhiteSpace: true
        },
        sales_price: {
          checkForWhiteSpace: true
        },
        'product_gallery[]': {
          filesize: 2048,
          extension: "jpg|jpeg|png"
        }
      },
      messages: {
        product_name: {
          required: "Product name is required."
        },
        sku: {
          // required: "sku is required.",
          checkForWhiteSpace: "Spaces are not allowed."
        },
        artisan_id: {
          required: "Artisan name is required."
        },
        stock_status: {
          required: "stock status is required."
        },
        category_id: {
          required: "category is required."
        },
        sub_category_id: {
          required: "sub category is required."
        },
        base_price: {
          required: "base price is required."
        },
        hsn_code: {
          required: "hsn code is required."
        },
        sales_price: {
          required: "sales price is required."
        },
        'product_gallery[]': {
          required: "Product Gallery Images are required."
        },
        product_image: {
          required: "Product Image is required"
        }
      }
    });
    $('#addProductBtn').click(function (e) {
      var btn = $(this);
      var form = $(this).closest('form');

      if (!form.valid()) {
        $('#is_approved').val('0');
        return;
      }

      var formData = new FormData();
      var submitedFiles = document.getElementsByClassName('form-files');

      var _iterator4 = _createForOfIteratorHelper(submitedFiles),
          _step4;

      try {
        for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
          item = _step4.value;

          if (item.files && item.files[0]) {
            var extension = item.files[0].name.split('.');
            var temp2 = extension[extension.length - 1].toLowerCase();
            var size = parseFloat(item.files[0].size / 1024).toFixed(2);

            if (size > 2048) {
              toastr.warning("Maximum upload file size is 2MB", "Size Alert");
              return false;
            } else {
              formData.append(item.name, item.files[0]);
            }
          }
        }
      } catch (err) {
        _iterator4.e(err);
      } finally {
        _iterator4.f();
      }

      var totalfiles = document.getElementById('product_gallery').files.length;

      for (var index = 0; index < totalfiles; index++) {
        var item = document.getElementById('product_gallery');
        var extension = item.files[0].name.split('.');
        var temp2 = extension[extension.length - 1].toLowerCase();
        var size = parseFloat(item.files[0].size / 1024).toFixed(2);

        if (size > 2048) {
          toastr.warning("Maximum upload file size is 2MB", "Size Alert");
          return false;
        } else {
          formData.append(item.name, document.getElementById('product_gallery').files[index]);
        }
      }

      form.serializeArray().forEach(function (field) {
        if (field.value.trim() != '' || field.value.trim() != null) {
          formData.append(field.name, field.value);
        }
      }); // console.log(formData);
      // return false;
      // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      AmouneeApp.disableButtonWithLoading(btn);
      $.ajax({
        url: form.attr("data-action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      }); // Disabling button with Loading

      AmouneeApp.disableButtonWithLoading(btn);
    });
    $('#addProductApproveBtn').click(function (e) {
      $('#is_approved').val('1');
      $('#addProductBtn').trigger('click');
    });
    $("#addProductResetBtn").click(function (e) {
      $("#tax_status").val('Taxable');
      $("#tax_status").trigger('change');
    });
  };

  var handleManageStockModal = function handleManageStockModal() {
    // $(document).on('click', '.manage_stock_btn', function (e) {
    //     $("#manageStockForm").attr("data-action",$(this).attr("data-url"));
    //     $("#stockModal").modal('show');
    // });
    $(".manage_stock_btn").click(function () {
      var btn = $(this);
      var product_stock_url = btn.attr("data-action");
      var redirect_url = btn.attr("data-redirect-url");
      $("#manageStockForm").attr("action", product_stock_url);
      $("#manageStockForm").attr("data-redirect-url", redirect_url);
    });
    $('#stockModal').on('hidden.bs.modal', function () {
      AmouneeApp.resetForm($('#manageStockForm'));
    });
  };

  var initProductEditForm = function initProductEditForm() {
    $("#base_price").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) //prouduct level commission exist
        {
          commission = parseInt(commission);
          base_price = parseInt(base_price);

          if (commission_unit == 'rupee') {
            var sales_price = base_price + commission;
            $('#sales_price').val(sales_price);
          } else if (commission_unit == 'percentage') {
            var tmp = commission * base_price / 100;
            var sales_price = base_price + tmp;
            $('#sales_price').val(sales_price);
          } else {
            $('#sales_price').val('');
          }
        } else //check for sub category level , artisan level and global level commission
        {
          //code for fetching subcategory level commission
          var sub_category_commission;
          var sales_price = parseInt(base_price);
          var id = $("#sub_category_id").val();

          if (id) {
            $.ajax({
              url: 'http://amounee.test/category/' + id + '/details',
              method: 'get',
              async: false,
              success: function success(res) {
                sub_category_commission = parseInt(res.commission);
                sub_category_commission = sub_category_commission * base_price / 100;

                if (sub_category_commission) {
                  sales_price += sub_category_commission;
                }

                $('#sales_price').val(sales_price);
              }
            });
          }

          if (!sub_category_commission) {
            //check for artisan level commission
            var artisan_commission;
            var artisan_id = $('#artisan_id').val();

            if (artisan_id) {
              $.ajax({
                url: 'http://amounee.test/artisan/' + artisan_id + '/details',
                method: 'get',
                async: false,
                success: function success(res) {
                  artisan_commission = parseInt(res.commission);
                  artisan_commission = artisan_commission * base_price / 100;

                  if (artisan_commission) {
                    sales_price += artisan_commission;
                  }

                  $('#sales_price').val(sales_price);
                }
              });
            }
          } //global level commission


          if (base_price == sales_price) {
            var sales_price;
            commission = 25 * base_price / 100;
            sales_price += commission;
            $('#sales_price').val(sales_price);
          }
        }
    });
    $("#commission").change(function () {
      var base_price = $('#base_price').val();

      if (!base_price) {
        base_price = 0;
      }

      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) {
        commission = parseInt(commission);
        base_price = parseInt(base_price);

        if (commission_unit == 'rupee') {
          var sales_price = base_price + commission;
          $('#sales_price').val(sales_price);
        } else if (commission_unit == 'percentage') {
          var tmp = commission * base_price / 100;
          var sales_price = base_price + tmp;
          $('#sales_price').val(sales_price);
        } else {
          $('#sales_price').val('');
        }
      } else {
        var sales_price = parseInt(base_price);
      }

      $('#sales_price').val(sales_price);
    });
    $("#commission_unit").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $(this).val();

      if (commission) {
        commission = parseInt(commission);
        base_price = parseInt(base_price);

        if (commission_unit == 'rupee') {
          var sales_price = base_price + commission;
          $('#sales_price').val(sales_price);
        } else if (commission_unit == 'percentage') {
          var tmp = commission * base_price / 100;
          var sales_price = base_price + tmp;
          $('#sales_price').val(sales_price);
        } else {
          $('#sales_price').val('');
        }
      } else {
        var sales_price = parseInt(base_price);
      }

      $('#sales_price').val(sales_price);
    });
    $("#artisan_id").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) //prouduct level commission exist
        {
          commission = parseInt(commission);
          base_price = parseInt(base_price);

          if (commission_unit == 'rupee') {
            var sales_price = base_price + commission;
            $('#sales_price').val(sales_price);
          } else if (commission_unit == 'percentage') {
            var tmp = commission * base_price / 100;
            var sales_price = base_price + tmp;
            $('#sales_price').val(sales_price);
          } else {
            $('#sales_price').val('');
          }
        } else //check for sub category level , artisan level and global level commission
        {
          //code for fetching subcategory level commission
          var sub_category_commission;
          var sales_price = parseInt(base_price);
          var id = $("#sub_category_id").val();

          if (id) {
            $.ajax({
              url: 'http://amounee.test/category/' + id + '/details',
              method: 'get',
              async: false,
              success: function success(res) {
                sub_category_commission = parseInt(res.commission);
                sub_category_commission = sub_category_commission * base_price / 100;

                if (sub_category_commission) {
                  sales_price += sub_category_commission;
                }

                $('#sales_price').val(sales_price);
              }
            });
          }

          if (!sub_category_commission) {
            //check for artisan level commission
            var artisan_commission;
            var artisan_id = $('#artisan_id').val();

            if (artisan_id) {
              $.ajax({
                url: 'http://amounee.test/artisan/' + artisan_id + '/details',
                method: 'get',
                async: false,
                success: function success(res) {
                  artisan_commission = parseInt(res.commission);
                  artisan_commission = artisan_commission * base_price / 100;

                  if (artisan_commission) {
                    sales_price += artisan_commission;
                  }

                  $('#sales_price').val(sales_price);
                }
              });
            }
          } //global level commission


          if (base_price == sales_price) {
            var sales_price;
            commission = 25 * base_price / 100;
            sales_price += commission;
            $('#sales_price').val(sales_price);
          }
        }
    });
    $("#sub_category_id").change(function () {
      var base_price = $('#base_price').val();
      var commission = $('#commission').val();
      var commission_unit = $('#commission_unit').val();

      if (commission) //prouduct level commission exist
        {
          commission = parseInt(commission);
          base_price = parseInt(base_price);

          if (commission_unit == 'rupee') {
            var sales_price = base_price + commission;
            $('#sales_price').val(sales_price);
          } else if (commission_unit == 'percentage') {
            var tmp = commission * base_price / 100;
            var sales_price = base_price + tmp;
            $('#sales_price').val(sales_price);
          } else {
            $('#sales_price').val('');
          }
        } else //check for sub category level , artisan level and global level commission
        {
          //code for fetching subcategory level commission
          var artisan_commission;
          var sub_category_commission;
          var sales_price = parseInt(base_price);
          var id = $("#sub_category_id").val();

          if (id) {
            $.ajax({
              url: 'http://amounee.test/category/' + id + '/details',
              method: 'get',
              async: false,
              success: function success(res) {
                sub_category_commission = parseInt(res.commission);
                sub_category_commission = sub_category_commission * base_price / 100;

                if (sub_category_commission) {
                  sales_price += sub_category_commission;
                }

                $('#sales_price').val(sales_price);
              }
            });
          }

          if (!sub_category_commission) {
            //check for artisan level commission
            var sales_price = parseInt(base_price);
            var artisan_id = $('#artisan_id').val();

            if (artisan_id) {
              $.ajax({
                url: 'http://amounee.test/artisan/' + artisan_id + '/details',
                method: 'get',
                async: false,
                success: function success(res) {
                  artisan_commission = parseInt(res.commission);
                  artisan_commission = artisan_commission * base_price / 100;

                  if (artisan_commission) {
                    sales_price += artisan_commission;
                  }

                  $('#sales_price').val(sales_price);
                }
              });
            }
          } //global level commission


          if (base_price == sales_price) {
            var sales_price;
            commission = 25 * base_price / 100;
            sales_price += commission;
            $('#sales_price').val(sales_price);
          }
        }
    });
    $('#stock_status').change(function () {
      var stock_status = $("#stock_status").val();

      if (stock_status == 'In stock') {
        $('#stock_div').show();
      } else {
        $('#stock').val("");
        $('#stock_div').hide();
      }
    });
    var preview = ["no image"];
    var previewCaption = "Select Product Image";
    var broweseLabel = "Browse";
    var showRemove = true;

    if ($("#edit_product_image").is("[data-product-image-url]")) {
      preview = ["<img src='" + $("#edit_product_image").attr("data-product-image-url") + "' height='180px' width='180px'>"];
      previewCaption = "1 Product Picture uploaded";
      broweseLabel = "Change";
      showRemove = false;
    }

    $("#edit_product_image").fileinput({
      showUpload: false,
      showRemove: showRemove,
      previewFileType: 'image',
      minFileCount: 1,
      maxFileCount: 1,
      allowedFileExtensions: ['jpeg', 'jpg', 'png'],
      minFileSize: 1,
      //maxFileSize: 2000,
      msgInvalidFileExtension: 'Please select JPG, JPEG or PNG file.',
      browseLabel: broweseLabel,
      browseClass: 'btn btn-sm btn-info m-btn--air',
      removeClass: 'btn btn-sm btn-danger m-btn--air',
      initialPreview: preview,
      initialCaption: previewCaption
    });
    $("#edit_product_image").trigger('fileclear');
  };

  var handleproductUpdate = function handleproductUpdate() {
    $('#updateProductBtn').click(function (e) {
      var btn = $(this);
      var form = $(this).closest('form');

      if (!form.valid()) {
        return;
      }

      var formData = new FormData();
      var submitedFiles = document.getElementsByClassName('form-files');

      var _iterator5 = _createForOfIteratorHelper(submitedFiles),
          _step5;

      try {
        for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
          item = _step5.value;

          if (item.files && item.files[0]) {
            var extension = item.files[0].name.split('.');
            var temp2 = extension[extension.length - 1].toLowerCase();
            var size = parseFloat(item.files[0].size / 1024).toFixed(2);

            if (size > 2048) {
              toastr.warning("Maximum upload file size is 2MB", "Size Alert");
              return false;
            } else {
              formData.append(item.name, item.files[0]);
            }
          }
        }
      } catch (err) {
        _iterator5.e(err);
      } finally {
        _iterator5.f();
      }

      var totalfiles = document.getElementById('product_gallery').files.length;

      for (var index = 0; index < totalfiles; index++) {
        var item = document.getElementById('product_gallery');
        var extension = item.files[0].name.split('.');
        var temp2 = extension[extension.length - 1].toLowerCase();
        var size = parseFloat(item.files[0].size / 1024).toFixed(2);

        if (size > 2048) {
          toastr.warning("Maximum upload file size is 2MB", "Size Alert");
          return false;
        } else {
          formData.append(item.name, document.getElementById('product_gallery').files[index]);
        }
      }

      form.serializeArray().forEach(function (field) {
        if (field.value.trim() != '' || field.value.trim() != null) {
          formData.append(field.name, field.value);
        }
      }); // console.log(formData);
      // return false;
      // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

      AmouneeApp.disableButtonWithLoading(btn);
      $.ajax({
        url: form.attr("data-action"),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
          AmouneeApp.displayResultWithCallback(data, function () {
            if (btn.data('additional-callback') != '') {
              var additional_callback = eval(btn.data('additional-callback'));

              if (typeof additional_callback == 'function') {
                additional_callback(data);
              }
            }

            if (form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined) {
              window.location.href = form.attr("data-redirect-url");
            } else if (btn.data('scroll') == 'off') {} else {
              $('html, body').animate({
                scrollTop: 0
              }, 'slow');
            }

            AmouneeApp.enableButton(btn);
          }, function () {
            AmouneeApp.enableButton(btn);
          });
        },
        error: function error(data) {
          AmouneeApp.displayFailedValidation(data);
          AmouneeApp.enableButton(btn);
        }
      }); // Disabling button with Loading

      AmouneeApp.disableButtonWithLoading(btn);
    });
  };

  return {
    init: function init() {
      handleManageStock();
      handleManageStockModal();
      initProductCreateForm();
      handleProductStore();
      initProductEditForm();
      handleproductUpdate();
    }
  };
}();

jQuery(document).ready(function () {
  Product.init();
});

var Status = function () {
  var handleApproveReq = function handleApproveReq() {
    $(document).on('click', '.pin-approve', function (e) {
      e.preventDefault();
      var btn = $(this);
      btn.tooltip('hide');
      AmouneeApp.disableButton(btn);
      $.post(btn.attr("data-action")).done(function (data) {
        TBHList.listButtonAjaxResultWithToastr(data, btn);
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  var handleRejectReq = function handleRejectReq() {
    $(document).on('click', '.pin-reject', function (e) {
      e.preventDefault();
      var btn = $(this);
      btn.tooltip('hide');
      AmouneeApp.disableButton(btn);
      $.post(btn.attr("data-action")).done(function (data) {
        TBHList.listButtonAjaxResultWithToastr(data, btn);
      }).fail(function (data) {
        AmouneeApp.displayFailedValidation(data);
        AmouneeApp.enableButton(btn);
      });
    });
  };

  return {
    init: function init() {
      handleApproveReq();
      handleRejectReq();
    }
  };
}();

jQuery(document).ready(function () {
  Status.init();
});
