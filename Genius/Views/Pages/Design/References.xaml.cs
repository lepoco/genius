// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Collections.Generic;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;

namespace Genius.Views.Pages.Design
{
    /// <summary>
    /// Interaction logic for Relations.xaml
    /// </summary>
    public partial class References : Page
    {
        private List<string> _productsNames, _conditionsNames;
        public References()
        {
            InitializeComponent();
            
            DataContext = this;
            this.UpdateComboBoxes();
            this.UpdateList();
        }

        public void OnRequestNavigate()
        {
            this.UpdateComboBoxes();
        }

        private void UpdateComboBoxes()
        {
            this._productsNames = new List<string> { };
            foreach (Code.Engine.Product singleProduct in Code.GH.CurrentExpertSystem.KnowledgeBase.Products)
                this._productsNames.Add(singleProduct.Name);

            comboboxProduct.ItemsSource = this._productsNames;

            this._conditionsNames = new List<string> { };
            foreach (Code.Engine.Condition singleCondition in Code.GH.CurrentExpertSystem.KnowledgeBase.Conditions)
                this._conditionsNames.Add(singleCondition.Name);

            comboboxCondition.ItemsSource = this._conditionsNames;
        }

        private async void UpdateList()
        {
            //Move the class cast to another thread in case we are dealing with a huge amount of data.
            await Task.Run(() =>
            {
                List<Code.UI.VReference> visualReferences = new List<Code.UI.VReference> { };
                Code.UI.VReference casted;

                foreach (var singleReference in Code.GH.CurrentExpertSystem.KnowledgeBase.References)
                {
                    casted = new Code.UI.VReference();
                    casted.Cast(singleReference);
                    visualReferences.Add(casted);
                }

                //Abort if user suddenly terminates applications.
                if (Application.Current == null)
                    return;

                Application.Current.Dispatcher.Invoke(() =>
                {
                    listReferences.ItemsSource = null;
                    //listReferences.ItemsSource = Code.GH.CurrentExpertSystem.KnowledgeBase.References;
                    listReferences.ItemsSource = visualReferences;
                });
            });
        }

        private void AddButton_Click(object sender, RoutedEventArgs e)
        {
            gridAddReference.Visibility = Visibility.Visible;
        }

        private void SaveButton_Click(object sender, RoutedEventArgs e)
        {
            int productIndex = comboboxProduct.SelectedIndex;
            int conditionIndex = comboboxCondition.SelectedIndex;

            if (productIndex > (Code.GH.CurrentExpertSystem.KnowledgeBase.Products.Count - 1))
                return; //error

            if (conditionIndex > (Code.GH.CurrentExpertSystem.KnowledgeBase.Conditions.Count - 1))
                return; //error

            Code.GH.CurrentExpertSystem.KnowledgeBase.AddReference(new Code.Engine.Reference
            {
                ProductId = Code.GH.CurrentExpertSystem.KnowledgeBase.Products[productIndex].ID,
                ConditionId = Code.GH.CurrentExpertSystem.KnowledgeBase.Conditions[conditionIndex].ID,
                Fulfil = true
            });
            gridAddReference.Visibility = Visibility.Hidden;

            //For deletion, sorting each time is inefficient
            Code.GH.CurrentExpertSystem.KnowledgeBase.SortReferencesByProduct();
            this.UpdateList();
        }

        private void ComboBoxProduct_OnPreviewTextInput(object sender, TextCompositionEventArgs e)
        {
            comboboxProduct.IsDropDownOpen = true;
        }

        private void ComboBoxCondition_OnPreviewTextInput(object sender, TextCompositionEventArgs e)
        {
            comboboxCondition.IsDropDownOpen = true;
        }
    }
}
