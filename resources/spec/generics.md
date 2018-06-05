# Generics example

A:

```graphql
type Name($variable: Object) { # "Object" should be type definition
    a: $variable
    
    b: [$variable!]!
    
    c: $variable(a: ID)
    
    d: $variable(a: $variable, b: ID(some: $variable))
}
```

B:

```graphql
type Example implements InterfaceExample(variable: Example) {
    a: Type(variable: Example)
    
    b: [Type(variable: Example)!]!
    
    c: Type(variable: Example(variable: Type))
    
    d: [Type(variable: Example(variable: Type))!]!
}
```

C:

```graphql
interface C($arg: Scalar) {
    field: $arg
}

type A($argA: Float) implements C(arg: $argA) {
    field: $argA
}

type B($a: C) {
    a: $a(field: Int)!
}

#
# B(a: A)       -> type B { a: A(field: Int)! }
# A(field: Int) -> type A implements C(argC: Int) { field: Int }
# C(argC: Int)  -> interface C { field: Int! }
#
# >>>>>>>>>>>>>>>>>>> OUTPUT >>>>>>>>>>>>>>>>>>>
#
# interface C { field: Int } 
# type A implements C { field: Int }
# type B { a: A! }
# 
```

## Def

Type:

```graphql
type A($var: TypeHint) {}
interface A($var: TypeHint) {}

# ($var: TypeHint) === #TypeArguments AST
```

Call:

```graphql
type X implements Call(arg: Value) {}
type X implements Call(arg: $variable) {}
type X implements $variable {}
type X {
    f1: Call(arg: Value)
    f2: Call(arg: $variable)
    f3: $variable
    f4: $variable(arg: Value)
    f4: $variable(arg: $variable)
    
    a1(arg: Call(arg: Value)): Result
    a2(arg: Call(arg: $variable)): Result
    a3(arg: $variable): Result
    a4(arg: $variable(arg: Value)): Result
    a4(arg: $variable(arg: $variable)): Result
}

# Call(arg: Value) === #TypeInvocation AST
```
