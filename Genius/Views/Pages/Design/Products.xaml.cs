// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Windows;
using System.Windows.Controls;

namespace Genius.Views.Pages.Design
{
    /// <summary>
    /// Interaction logic for Products.xaml
    /// </summary>
    public partial class Products : Page
    {
        public Products()
        {
            InitializeComponent();
            dialogAddProduct.ActionButtonGlyph = MaterialWPF.UI.MiconIcons.CalculatorAddition;
            dialogAddProduct.ActionButtonClick = DialogAdd_Click;

            this.UpdateList();
        }

        public void OnRequestNavigate()
        {
            
        }

        private void UpdateList()
        {
            listProducts.ItemsSource = null;
            listProducts.ItemsSource = Code.GH.CurrentExpertSystem.KnowledgeBase.Products;
        }

        private void DialogAdd_Click()
        {
            if (string.IsNullOrEmpty(textboxProductName.Text))
            {
                Code.GH.GlobalSnackbar.Header = "An error occured!";
                Code.GH.GlobalSnackbar.Message = "The new product must have a name.";
                Code.GH.GlobalSnackbar.Show();
                return;
            }

            Code.GH.CurrentExpertSystem.KnowledgeBase.AddProduct(textboxProductName.Text, textboxProductDescription.Text);

            textboxProductName.Text = textboxProductDescription.Text = "";
            dialogAddProduct.Hide();
            this.UpdateList();
        }

        private void AddButton_Click(object sender, RoutedEventArgs e)
        {
            dialogAddProduct.Show();
        }
    }
}
