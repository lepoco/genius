using Genius.Data.Models.Expert;
using System.Collections.Generic;

namespace Genius.Expert.Interfaces
{
    public interface ISolverResponse
    {
        public int SystemId { get; set; }

        public bool IsMultiple { get; set; }

        public bool IsSolved { get; set; }

        public SolverStatus Status { get; set; }

        public IEnumerable<Condition> NextConditions { get; set; }

        public IEnumerable<Product> ResultingProducts { get; set; }

    }
}
