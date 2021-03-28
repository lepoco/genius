// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Windows;
using System.Windows.Controls;

namespace Genius.Views.Pages.Analysis
{
    /// <summary>
    /// Interaction logic for Workflow.xaml
    /// </summary>
    public partial class Workflow : Page
    {
        private Code.Engine.Predictor.Solver _currentSolver;
        public Workflow()
        {
            InitializeComponent();
            this.InitializeWorkflow();
        }

        private void InitializeWorkflow()
        {
            this._currentSolver = new Code.Engine.Predictor.Solver();
            this._currentSolver.LoadExpertSystem(Code.GH.CurrentExpertSystem);


            textQuestionText.Text = Code.GH.CurrentExpertSystem.Question;
        }

        private void ActionButton_Click(object sender, RoutedEventArgs e)
        {
            string tag = (sender as MaterialWPF.Controls.MaterialButton).Tag.ToString();
#if DEBUG
            System.Diagnostics.Debug.WriteLine("Clicked: " + tag);
#endif
        }
    }
}
