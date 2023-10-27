<?php

    interface RPN{
        public function calculate(string $input): float;
    }

    class ONP implements RPN{

        public function calculate(string $input): float{
            $stack = []; #stos do odkładania liczb
            $items = explode(' ', $input); #odseparowanie liczb i znaków
    
            foreach ($items as $item) {
                if (is_numeric($item)) {
                    array_push($stack, $item); #jeśli jest liczbą to dodaj do stosu
                } elseif (self::isOperator($item)) { #jeśli jest operatorem
                    if (count($stack) < 2) {
                        throw new InvalidArgumentException("Invalid RPN expression: $input"); #jeśli jest operatorem, a jest jedna liczba to błąd
                    }
                    $operand2 = array_pop($stack); #zdejmij liczbe ze stosu
                    $operand1 = array_pop($stack); #zdejmij liczbe ze stosu
                    $result = self::evaluateExpression($operand1, $operand2, $item); #wykonaj działanie
                    array_push($stack, $result); #dodaj wynik na stos
                } else {
                    throw new InvalidArgumentException("Invalid token: $item in RPN expression: $input"); #nie jest ani liczba ani operatorem
                }
            }
    
            if (count($stack) !== 1) { #jeśli na stosie jest więcej niż 1 rzecz (wynik) to błąd
                throw new InvalidArgumentException("Invalid RPN expression: $input");
            }
    
            return $stack[0];
        }
    
        private static function isOperator($token) {
            return in_array($token, ['+', '-', '*', '/']); #sprawdzanie czy jest operatorem
        }
    
        private static function evaluateExpression($operand1, $operand2, $operator) {
            switch ($operator) {
                case '+':
                    return $operand1 + $operand2;
                case '-':
                    return $operand1 - $operand2;
                case '*':
                    return $operand1 * $operand2;
                case '/':
                    if ($operand2 == 0) {
                        throw new InvalidArgumentException("Division by zero is not allowed");
                    }
                    return $operand1 / $operand2;
                default:
                    throw new InvalidArgumentException("Unsupported operator: $operator");
            }
        }
    }

    $onpExpression = "3 4 + 2 * 1 + 5 6 - *";
    $a = new ONP;
    echo $a->calculate($onpExpression);
?>
