{ pkgs ? import <unstable> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    # PHP
    php74
    php74.packages.composer
  ];
}
